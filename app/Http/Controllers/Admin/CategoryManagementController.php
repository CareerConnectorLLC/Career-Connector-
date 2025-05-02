<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

use function Laravel\Prompts\error;

class CategoryManagementController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return  $this->getCategories($request);
            } else {
                return Inertia::render('Admin/category/List');
            }

        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getCategories(Request $request)
    {
        try {
            $sortBy = [
                'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
                'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
            ];

            $filter = isset($request->filters) ? $request->filters : [];

            $categories = Category::with('services')
            ->filter($filter)
            ->ordering($sortBy)
            ->orderBy('id','desc')
            ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
            return response()->json($categories);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/category/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required',
            'description'   => 'required',
            'image'         => 'required',
            'active'        => 'required'
        ],[
            'active.required'   => 'You must choose a status'
        ]);

        try {
            $category = new Category();
            $category->name = $request->name;
            $category->description = $request->description;
            $category->active = $request->active;
            
            if ($request->file('image')) {
                if (!empty($category->image_url) && Storage::disk('public')->exists($category->image_url)) {
                    Storage::disk('public')->delete($category->image_url);
                }
                $category->image_url = $request->file('image')->store('image', 'public');
            }

            $category->save();
            session()->flash('success', 'Service has been created successfully');
            return response()->json(['redirectURL' => route('admin.category.list')]);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server Error');
        }
    }

    public function edit(Request $request, string $id)
    {   
        try {
            $category = Category::find($id);
            return Inertia::render('Admin/category/CreateEdit', compact('category'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update(Request $request, string $id)
    {   
        $request->validate([
            'name'          => 'required',
            'description'   => 'required',
            'image'         => 'required',
            'active'        => 'required'
        ],[
            'active.required'   => 'You must choose a status'
        ]);

        try {
            $category = Category::findOrFail($id);
            $category->name = $request->name;
            $category->description = $request->description;
            $category->active = $request->active;

            if ($request->file('image')) {
                if (!empty($category->image_url) && Storage::disk('public')->exists($category->image_url)) {
                    Storage::disk('public')->delete($category->image_url);
                }
                $category->image_url = $request->file('image')->store('image', 'public');
            }
            
            if (!$request->file('image') && $category->image_url) {
                if (Storage::disk('public')->exists($category->image_url)) {
                    Storage::disk('public')->delete($category->image_url);
                }
                $category->image_url = null;
            }

            $category->update();
            DB::commit();
            session()->flash('success', 'Category has been updated successfully');
            return response()->json(['redirectURL' => route('admin.category.list')]);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());

        }
    }

    public function remove(string $id)
    {
        try {
            $category = Category::find($id);
            if (is_null($category)) {
                throw new NotFoundResourceException("The Category with ID $id does not exist.");
            }
            $category->delete();
            session()->flash('success', 'Category has been deleted successfully.');
            return response()->json(['redirectUrl' => route('admin.category.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

}
