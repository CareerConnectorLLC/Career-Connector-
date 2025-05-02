<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class BlogCategoryManagementController extends Controller
{
    public function index(Request $request)
    {
        try {
            if ($request->isMethod('post')) {
                return  $this->getBlogCategories($request);
            } else {
                return Inertia::render('Admin/blog-category/List');
            }

        } catch (\Exception $e) {

            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getBlogCategories(Request $request)
    { 
        $sortBy = [
            'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
            'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
        ];

        $filter = isset($request->filters) ? $request->filters : [];
        $inquiries = BlogCategory::filter($filter)
        ->ordering($sortBy)
        ->orderBy('id','desc')
        ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
        return response()->json($inquiries);
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/blog-category/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function store( Request $request )
    {   
        $request->validate([
            'name'      => 'required|unique:blog_categories,name',
            'active'    => 'required'

        ],[
            'active.required'   =>  'The status field is required',
            'name.unique'       => 'This blog category already exists.',
        ]);
        try {
            $blog_category = new BlogCategory();
            $blog_category->name = $request->name;
            $blog_category->active = $request->active;

            $blog_category->save();
            session()->flash('success', 'Blog Category Created Successfully');
            return response()->json(['redirectUrl' => route('admin.blog-category.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function edit(string $id)
    {   
        $blog_category = BlogCategory::find($id);
        try {
            return Inertia::render('Admin/blog-category/CreateEdit', compact('blog_category'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function update( Request $request, string $id )
    {   
        $request->validate([
            'name'      => 'required|unique:blog_categories,name,' . $id,
            'active'    => 'required'

        ],[
            'active.required'       =>  'The status field is required',
            'name.unique'       => 'This blog category already exists.',
        ]);
        try {
            $blog_category = BlogCategory::find($id);
            $blog_category->name = $request->name;
            $blog_category->active = $request->active;

            $blog_category->update();
            session()->flash('success', 'Blog Category Updated Successfully');
            return response()->json(['redirectUrl' => route('admin.blog-category.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function remove(string $id)
    {
        try {
            $blog_category = BlogCategory::find($id);
            if (is_null($blog_category)) {
                throw new NotFoundResourceException("The Blog Category with ID $id does not exist.");
            }
            $blog_category->delete();
            session()->flash('success', 'Blog Category Has Been Deleted Successfully.');
            return response()->json(['redirectUrl' => route('admin.blog-category.list')] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $blog_category = BlogCategory::find($id);
            $blog_category->active = ($blog_category->active == 1) ? 0 : 1;
            $blog_category->save();
            session()->flash('success', 'Blog successfully updated');
            return response()->json(['redirectUrl' => route('admin.blog-category.list')] , 200);
        } catch (\Exception $e) {
            Log::error(" :: NOT FOUND :: " . $e->getMessage());
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }
}
