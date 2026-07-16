<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class BlogController extends Controller
{
    public function index()
    {
        $posts = BlogPost::with('category')->latest()->paginate(15);
        return Inertia::render('Admin/Blog/Index', ['posts' => $posts]);
    }

    public function create()
    {
        $categories = BlogCategory::all();
        return Inertia::render('Admin/Blog/Create', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('featured_image')) {
            $imagePath = $request->file('featured_image')->store('blog', 'public');
        }

        BlogPost::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']) . '-' . Str::random(5),
            'blog_category_id' => $validated['blog_category_id'] ?? null,
            'content' => $validated['content'] ?? null,
            'featured_image' => $imagePath,
            'status' => $validated['status'],
        ]);

        return redirect()->route('admin.blog.index')->with('success', 'Blog post created.');
    }

    public function edit(BlogPost $blog)
    {
        $categories = BlogCategory::all();
        return Inertia::render('Admin/Blog/Edit', ['post' => $blog, 'categories' => $categories]);
    }

    public function update(Request $request, BlogPost $blog)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'blog_category_id' => 'nullable|exists:blog_categories,id',
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'featured_image' => 'nullable|image|max:2048',
        ]);

        $data = [
            'title' => $validated['title'],
            'blog_category_id' => $validated['blog_category_id'] ?? null,
            'content' => $validated['content'] ?? null,
            'status' => $validated['status'],
        ];

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('blog', 'public');
        }

        $blog->update($data);
        return redirect()->route('admin.blog.index')->with('success', 'Blog post updated.');
    }

    public function destroy(BlogPost $blog)
    {
        $blog->delete();
        return redirect()->back()->with('success', 'Blog post deleted.');
    }

    // Blog Categories
    public function categories()
    {
        $categories = BlogCategory::withCount('posts')->latest()->get();
        return Inertia::render('Admin/Blog/Categories', ['categories' => $categories]);
    }

    public function storeCategory(Request $request)
    {
        $validated = $request->validate(['name' => 'required|string|max:255']);
        BlogCategory::create(['name' => $validated['name'], 'slug' => Str::slug($validated['name'])]);
        return redirect()->back()->with('success', 'Category created.');
    }

    public function destroyCategory(BlogCategory $category)
    {
        $category->delete();
        return redirect()->back()->with('success', 'Category deleted.');
    }
}
