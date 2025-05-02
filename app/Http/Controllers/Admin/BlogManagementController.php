<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Symfony\Component\Translation\Exception\NotFoundResourceException;  

class BlogManagementController extends Controller
{
    public function index()
    {
        try {
            return Inertia::render('Admin/blog/List');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function getBlogs(Request $request)
    { 
        $sortBy = [
            'fieldName' => isset($request->sorts) ? $request->sorts['fieldName'] : '',
            'shortBy' => (isset($request->sorts) && $request->sorts['shortBy'] == -1) ? 'Desc' : 'Asc',
        ];

        $filter = isset($request->filters) ? $request->filters : [];
        $blogs = Blog::with(['user','category'])
        ->filter($filter)
        ->ordering($sortBy)
        ->orderBy('blogs.id','desc')
        ->paginate($request->per_page ?? 10, ['*'], 'page', $request->page ?? 1);
        return response()->json($blogs);
    }

    public function getBlogCategories()
    {
        try {
            $blog_categories = BlogCategory::all();
            return response()->json($blog_categories);
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function create()
    {
        try {
            return Inertia::render('Admin/blog/CreateEdit');
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function store( Request $request )
    {   
        $request->validate([
            'title'                 => 'required|string|max:255|unique:blogs,title',
            'text_content'          => 'required|string',
            'image'                 => 'nullable|max:1024',
            'blog_category_id'      => 'required',

        ],[
            'blog_category_id.required'     => 'You must choose a category'
        ]);


        try {
            $blog = new Blog();
            
            $blog->title = $request->title;
            $blog->user_id = Auth::id();
            $blog->text_content = $request->text_content;
            $blog->blog_category_id = $request->blog_category_id;

            if($request->file('image')){
                File::delete(storage_path('app/'.$blog->image_url));
                $blog->image_url = request()->file('image')->store('image');
              }

            $blog->save();
            return response()->json($blog);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function edit(string $id)
    {   
        $blog = Blog::with(['user','category'])->find($id);
        try {
            return Inertia::render('Admin/blog/CreateEdit', compact('blog'));
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function update(Request $request, string $id)
    {   
        $request->validate([
            'title'                 => 'required|string|max:255|unique:blogs,title,' . $id,
            'text_content'          => 'required|string',
            'image'                 => 'nullable|max:1024',
            'blog_category_id'      => 'required',
        ],[
            'blog_category_id.required'     => 'You must choose a category'
        ]);
    
        try {
            $blog = Blog::findOrFail($id);         
            $blog->title = $request->title;
            $blog->text_content = $request->text_content;
            $blog->blog_category_id = $request->blog_category_id;

            if ($request->file('image')) {
                if ($blog->image_url) {
                    Storage::disk('public')->delete($blog->image_url);
                }

                $blog->image_url = $request->file('image')->store('image', 'public');

            } elseif ($request->boolean('delete_photo') && $blog->image_url) {
                Storage::disk('public')->delete($blog->image_url);
                $blog->image_url = null;
            }

            $blog->update();
        
            return response()->json($blog);
            
        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }


    public function remove(string $id)
    {
        try {
            $blog = Blog::find($id);
            if (is_null($blog)) {
                throw new NotFoundResourceException("The Blog with ID $id does not exist.");
            }
            $blog->delete();
            session()->flash('success', 'Blog has been updated successfully.');
            return response()->json(['status' => true] , 200);

        } catch (\Exception $e) {
            Log::error(" :: EXCEPTION :: " . $e->getMessage() . "\n" . $e->getTraceAsString());
            return back()->with('error', 'Server error');
        }
    }

    public function changeStatus(string $id)
    {
        try {
            $blog = Blog::find($id);
            $blog->active = ($blog->active == 1) ? 0 : 1;
            $blog->save();
            session()->flash('success', 'Blog successfully updated');
            return response()->json(['redirectUrl' => route('admin.blog.list')] , 200);
        } catch (\Exception $e) {
            Log::error(" :: NOT FOUND :: " . $e->getMessage());
            return response()->json(['error' => 'Blog not found'], 404);
        }
    }
}
