<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Inertia\Inertia;
use App\Models\Service;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ServiceManagementController extends Controller
{   

    public function test()
    {
        $services = User::role('SERVICE-PROVIDER')->with(['services.category:id,name', 'availabilities'])->get();
        return response()->json($services);
    }
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return $this->getServiceProvidersList($request);
            } else {
                return Inertia::render('Admin/service/List');
            }

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getServiceProvidersList(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filter = isset($request->filters) ? $request->filters : [];

            $service_providers = Service::with(['category'])
            ->filter($filter)
            ->ordering($sortBy)
            ->orderBy('id','desc')
            ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
            return response()->json($service_providers);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getCategoryList()
    {
        $categories = Category::select('id', 'name')->get();
        return response()->json($categories);
    }

    public function create(){
        try {
            return Inertia::render('Admin/service/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => ['required', 'unique:services'],
            'description'   => 'nullable',
            'category_id'   => ['required', 'exists:categories,id'],
            'image'         => 'nullable'
        ],[
            'category_id.required' => 'You must choose a category'
        ]);

        try {
            $service = new Service();
            $service->name = $request->name;
            $service->description = $request->description;
            $service->category_id = $request->category_id;

            if ($request->file('image')) {
                File::delete(storage_path('app/'.$service->image_url));
                $service->image_url = '/storage/'. request()->file('image')->store('image');
            }

            $service->save();
            session()->flash('success', 'Service has been created successfully');
            return response()->json(['redirectURL' => route('admin.service.list')]);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
        }
    }


    public function edit(Request $request, string $id)
    {   
        try {
            $service = Service::find($id);
            return Inertia::render('Admin/service/CreateEdit', compact('service'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update(Request $request, string $id)
    {   
        $request->validate([
            'name'          =>  ['required', Rule::unique('services')->ignore($id)],
            'description'   =>  'nullable',
            'category_id'   =>  ['required', 'exists:categories,id'],
            'image'         =>  'nullable'
        ]);

        try {
            $service = Service::findOrFail($id);
            $service->name = $request->name;
            $service->description = $request->description;
            $service->category_id = $request->category_id;

            if ($request->file('image')) {
                if (!empty($service->image_url) && Storage::disk('public')->exists($service->image_url)) {
                    Storage::disk('public')->delete($service->image_url);
                }
                $service->image_url = $request->file('image')->store('image', 'public');
            }            

            $service->update();
            DB::commit();
            return response()->json(['redirectUrl' => route('admin.service.list')] , 200);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());

        }
    }

    public function remove(string $id)
    {
        try {
            $service = Service::find($id);
            if (is_null($service)) {
                throw new NotFoundResourceException("The Service with ID $id does not exist.");
            }
            $service->delete();
            session()->flash('success', 'Service has been deleted successfully.');
            return response()->json(['redirectUrl' => route('admin.service.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }
}
