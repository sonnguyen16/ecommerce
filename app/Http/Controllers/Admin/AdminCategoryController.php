<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminCategoryController extends Controller
{
    protected $mediaUrl;

    public function __construct()
    {
        $this->mediaUrl = config('app.url') . '/storage/';
    }

    /**
     * Display categories list
     */
    public function index()
    {
        $categories = Category::latest()->paginate(20);

        return Inertia::render('Admin/Categories/Index', [
            'categories' => $categories,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return Inertia::render('Admin/Categories/Edit', [
            'category' => null,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);

        return Inertia::render('Admin/Categories/Edit', [
            'category' => $category,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Store/Update category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $validated;

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('categories', 'public');
        }

        // Generate slug
        $data['slug'] = Str::slug($data['name']);

        if ($request->id) {
            $category = Category::findOrFail($request->id);
            $category->update($data);
        } else {
            Category::create($data);
        }

        return redirect()->route('admin.categories')->with('success', 'Lưu danh mục thành công');
    }

    /**
     * Delete category
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories')->with('success', 'Xóa danh mục thành công');
    }
}
