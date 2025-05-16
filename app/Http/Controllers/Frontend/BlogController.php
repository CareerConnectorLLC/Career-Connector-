<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Blog;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BlogController extends Controller
{
    public function index()
    {
        $posts = Blog::with('category:id,name')->where('active', true)->latest()->paginate(5);
        
        return Inertia::render('Frontend/blog/List', [
            'posts' => $posts
        ]);
    }

    public function show(Blog $blog)
    {
        $blog = $blog->load(['user', 'category:id,name']);
        
        $relatedPosts = Blog::where([
            ['blog_category_id', $blog->category->id],
            ['active', true],
            ['id', '!=', $blog->id]
        ])->with([
            'category:id,name',
            'user'
        ])->take(3)->get();

        return Inertia::render('Frontend/blog/Show', [
            'post' => $blog,
            'related_posts' => $relatedPosts
        ]);
    }
}
