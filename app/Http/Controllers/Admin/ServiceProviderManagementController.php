<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\User;
use Faker\Provider\ar_EG\Internet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ServiceProviderManagementController extends Controller
{
        public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return  $this->getServiceProvidersList($request);
            } else {
                return Inertia::render('Admin/service-provider/List');
            }

        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function show(Request $request, string $id)
    {   
        try {
            $bookings = $this->getBookings($id);
            // dd('testing');
            $service_provider = User::with(['services.category', 'availabilities', 'providerBookings.service', 'providerBookings'])->find($id);
            // $login_activity = Activity::where('log_name', 'user-logins')->where('causer_id', $service_provider->id)->latest()->take(3)->get();
            $login_activity = Activity::where('log_name', 'user-logins')
            ->where(function ($query) use ($service_provider) {
                $query->where('causer_id', $service_provider->id) 
                ->orWhereJsonContains('properties->email', $service_provider->email); 
            })
            ->latest()
            ->take(3)
            ->get();
            $service_provider->load('services.category');
            $categories = $service_provider->categories();
            return Inertia::render('Admin/service-provider/Show', compact('service_provider', 'categories', 'login_activity', 'bookings'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getBookings(string $id)
    {
        $bookings = Booking::where('provider_id', $id)->with(['client', 'service'])->orderBy('id', 'desc')->paginate(2);
        return response()->json($bookings);
    }

    public function getServiceProvidersList(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filters = isset($request->filters) ? $request->filters : [];

            // $filters = [
            //     ["column" => "category", "value" => ["Electrician"]],
            //     ["column" => "active", "value" => [1]]
            // ];
            

            $service_providers = User::role('SERVICE-PROVIDER')
            ->with('services.category')
            ->filter($filters)
            ->ordering($sortBy)
            ->orderBy('id','asc')
                ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
                // dd($service_providers);
            return response()->json($service_providers);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/service-provider/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'            => 'required',
            'middle_name'           => 'nullable',
            'last_name'             => 'required',
            'email'                 => 'required|email:rfc,dns|unique:users',
            'password'              => ['required',  Password::min(8)
                                    ->letters()
                                    ->mixedCase()
                                    ->numbers()
                                    ->symbols()],
            'password_confirmation' => 'required|same:password',
            'phone'                 => 'required|digits_between:10,15|unique:users,phone',
            'profile_photo'         => 'nullable|max:2048',
            'gender'                => 'required|in:male,female,others',
            'active'                => 'required|in:0,1',

        ],[
            'active.required'       =>  'The status field is required'
        ]);

        DB::beginTransaction();
        try {
            $service_provider = new User();
            $service_provider->first_name = $request->first_name;
            $service_provider->middle_name = $request->middle_name;
            $service_provider->last_name = $request->last_name;
            $service_provider->email = $request->email;
            $service_provider->phone = $request->phone;
            $service_provider->password = $request->password;
            $service_provider->gender = $request->gender;
            $service_provider->active = $request->active;
            if($request->file('profile_photo')){
                File::delete(storage_path('app/'.$service_provider->profile_photo_path));
                $service_provider->profile_photo_path = request()->file('profile_photo')->store('profile_image');
              }
            $service_provider->assignRole('SERVICE-PROVIDER');
            $service_provider->save();

            DB::commit();
            session()->flash('success', 'Service Providers successfully created');
            return response()->json(['redirectUrl' => route('admin.service-providers.list')] , 200);


        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $serviceProvider = User::find($id);
            return Inertia::render('Admin/service-provider/CreateEdit', compact('serviceProvider'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update(Request $request, string $id)
    {
        $service_provider = User::find($id);

        $request->validate([
            'first_name'            => 'required',
            'middle_name'           => 'nullable',
            'last_name'             => 'required',
            'email'                 => 'required|email:rfc,dns|unique:users,email,'.$id,
            'phone'                 => 'required|digits_between:10,15|unique:users,phone,'.$id,
            'profile_photo'         => 'nullable|max:2048',
            'gender'                => 'required|in:male,female,others',
            'active'                => 'required|in:0,1',
        ],[
            'active.required'       =>  'The status field is required'
        ]);
        DB::beginTransaction();
        try {
            $service_provider->first_name = $request->first_name;
            $service_provider->middle_name = $request->middle_name;
            $service_provider->last_name = $request->last_name;
            $service_provider->email = $request->email;
            $service_provider->phone = $request->phone;
            $service_provider->gender = $request->gender;
            $service_provider->active = $request->active;

            if($request->file('profile_photo')){
                $service_provider->profile_photo_path = request()->file('profile_photo')->store('profile_image');
            }

            if(!$request->file('profile_photo')){
                Storage::disk('public')->delete($service_provider->profile_photo_path);
                $service_provider->profile_photo_path = null;
            }

            $service_provider->update();
            DB::commit();
            return response()->json(['redirectUrl' => route('admin.service-providers.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function delete(string $id)
    {
        try {
            $service_provider = User::find($id);

            if (is_null($service_provider)) {
                throw new NotFoundResourceException("The Service Providers with ID '$id' does not exist.");
            }

            $service_provider->delete();
            session()->flash('success', 'Service Providers successfully deleted');
            return response()->json(['redirectUrl' => route('admin.service-providers.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $service_provider = User::find($id);
            $service_provider->active = ($service_provider->active == 1) ? 0 : 1;
            $service_provider->save();
            session()->flash('success', 'Service Providers successfully updated');
            return response()->json(['redirectUrl' => route('admin.service-providers.list')] , 200);
        } catch (\Exception $e) {
            Log::error(" :: NOT FOUND :: " . $e->getMessage());
            return response()->json(['error' => 'Service Providers not found'], 404);
        }
    }
}
