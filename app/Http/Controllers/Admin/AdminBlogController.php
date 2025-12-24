<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminBlogController extends Controller
{
    protected $mediaUrl;

    public function __construct()
    {
        $this->mediaUrl = config('app.url') . '/storage/';
    }

    /**
     * Display blogs list
     */
    public function index()
    {
        $blogs = Blog::latest()->paginate(20);

        return Inertia::render('Admin/Blogs/Index', [
            'blogs' => $blogs,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Admin/Blogs/Edit', [
            'blog' => null,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($slug)
    {
        $blog = Blog::where('slug', $slug)->orWhere('id', $slug)->firstOrFail();

        return Inertia::render('Admin/Blogs/Edit', [
            'blog' => $blog,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Store/Update blog
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'thumbnail' => 'nullable|image|max:2048',
            'is_public' => 'nullable|boolean',
        ]);

        $data = $validated;
        $data['is_public'] = $request->is_public ? 1 : 0;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('blogs', 'public');
        }

        // Generate slug
        $data['slug'] = Str::slug($data['title']);

        if ($request->id) {
            $blog = Blog::findOrFail($request->id);
            $blog->update($data);
        } else {
            Blog::create($data);
        }

        return redirect()->route('admin.blogs')->with('success', 'Lưu bài viết thành công');
    }

    /**
     * Delete blog
     */
    public function destroy($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->delete();

        return redirect()->route('admin.blogs')->with('success', 'Xóa bài viết thành công');
    }

    /**
     * Upload image for CKEditor
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'upload' => 'required|image|max:2048',
        ]);

        $path = $request->file('upload')->store('blogs', 'public');
        $url = config('app.url') . '/storage/' . $path;

        return response()->json([
            'uploaded' => true,
            'url' => $url,
        ]);
    }
}
