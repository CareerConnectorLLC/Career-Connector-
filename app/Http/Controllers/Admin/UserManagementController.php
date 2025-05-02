<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Inertia\Inertia;
use Spatie\Activitylog\Models\Activity;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class UserManagementController extends Controller
{
    public function index(Request $request)
    {   
        try {
            if ($request->isMethod('post')) {
                return  $this->getUserList($request);
            } else {
                return Inertia::render('Admin/user/List');
            }

        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getUserList(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filter = isset($request->filters) ? $request->filters : [];
            

            $users = User::
             filter($filter)
            ->role('USER')
            ->ordering($sortBy)
            ->orderBy('id','desc')
                ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1)
                ->withQueryString()
                ->through(function ($user) {
                    return [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'profile_photo_url' => $user->profile_photo_url,
                        'active' => $user->active,
                        'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                        'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                    ];
                });
            return response()->json($users);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function show(Request $request, string $id)
    {   
        try {
            // dd('testing');
            $user = User::find($id);
            // $login_activity = Activity::where('log_name', 'user-logins')->where('causer_id', $service_provider->id)->latest()->take(3)->get();
            $login_activity = Activity::where('log_name', 'user-logins')
            ->where(function ($query) use ($user) {
                $query->where('causer_id', $user->id) 
                ->orWhereJsonContains('properties->email', $user->email); 
            })
            ->latest()
            ->take(3)
            ->get();
            $user->load('services.category');
            $categories = $user->categories();
            return Inertia::render('Admin/user/Show', compact('user', 'categories', 'login_activity',));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/user/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name'            => 'required|string',
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
            $user = new User();
            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = $request->password;
            $user->gender = $request->gender;
            $user->active = $request->active;
            if($request->file('profile_photo')){
                File::delete(storage_path('app/'.$user->profile_photo_path));
                $user->profile_photo_path = request()->file('profile_photo')->store('profile_image');
              }
            $user->save();

            $user->assignRole('USER');
            DB::commit();
            session()->flash('success', 'User successfully created');
            return response()->json(['redirectUrl' => route('admin.users.list')] , 200);


        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function edit(string $id)
    {
        try {
            $user = User::find($id);
            return Inertia::render('Admin/user/CreateEdit', compact('user'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update(Request $request, string $id)
    {   
        $user = User::find($id);

        $request->validate([
            'first_name'            => 'required|string',
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
            $user->first_name = $request->first_name;
            $user->middle_name = $request->middle_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->gender = $request->gender;
            $user->active = $request->active;

            if($request->file('profile_photo')){
                $user->profile_photo_path = request()->file('profile_photo')->store('profile_image');
            }
            if(!$request->file('profile_photo')){
                Storage::disk('public')->delete($user->profile_photo_path);
                $user->profile_photo_path = null;
            }
            $user->update();
            DB::commit();
            return response()->json(['redirectUrl' => route('admin.users.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function delete(string $id)
    {
        try {
            $user = User::find($id);

            if (is_null($user)) {
                throw new NotFoundResourceException("The User with ID '$id' does not exist.");
            }

            $user->delete();
            session()->flash('success', 'User successfully deleted');
            return response()->json(['redirectUrl' => route('admin.users.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json(['error' => 'Internal server error'], 500);
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $user = User::find($id);
            $user->active = ($user->active == 1) ? 0 : 1;
            $user->save();
            session()->flash('success', 'User successfully updated');
            return response()->json(['redirectUrl' => route('admin.users.list')] , 200);
        } catch (\Exception $e) {
            Log::error(" :: NOT FOUND :: " . $e->getMessage());
            return response()->json(['error' => 'User not found'], 404);
        }
    }

}
