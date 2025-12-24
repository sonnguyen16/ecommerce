<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class AdminProductController extends Controller
{
    protected $mediaUrl;

    public function __construct()
    {
        $this->mediaUrl = config('app.url') . '/storage/';
    }

    /**
     * Display products list
     */
    public function index()
    {
        $products = Product::with('category')->latest()->paginate(20);

        return Inertia::render('Admin/Products/Index', [
            'products' => $products,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show create form
     */
    public function create()
    {
        $categories = Category::all();

        return Inertia::render('Admin/Products/Edit', [
            'product' => null,
            'categories' => $categories,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $product = Product::with('images')->findOrFail($id);
        $categories = Category::all();

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Store/Update product
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'unit' => 'nullable|string|max:50',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'thumbnail' => 'nullable|image|max:2048',
            'seo_title' => 'nullable|string|max:255',
            'seo_description' => 'nullable|string',
            'seo_url' => 'nullable|string|max:255',
            'attributes' => 'nullable|string',
        ]);

        $data = $validated;

        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $data['thumbnail'] = $request->file('thumbnail')->store('products', 'public');
        }

        // Generate slug
        if (empty($data['seo_url'])) {
            $data['slug'] = Str::slug($data['name']);
        } else {
            $data['slug'] = Str::slug($data['seo_url']);
        }

        // Parse attributes
        if (!empty($data['attributes'])) {
            $lines = explode("\n", $data['attributes']);
            $attrs = [];
            foreach ($lines as $line) {
                if (strpos($line, ':') !== false) {
                    [$key, $value] = explode(':', $line, 2);
                    $attrs[trim($key)] = trim($value);
                }
            }
            $data['attributes'] = json_encode($attrs);
        }

        if ($request->id) {
            $product = Product::findOrFail($request->id);
            $product->update($data);
        } else {
            $product = Product::create($data);
        }

        // Handle multiple images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                $product->images()->create(['path' => $path]);
            }
        }

        return redirect()->route('admin.products')->with('success', 'Lưu sản phẩm thành công');
    }

    /**
     * Delete product
     */
    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();

        return redirect()->route('admin.products')->with('success', 'Xóa sản phẩm thành công');
    }

    /**
     * Delete product image
     */
    public function deleteImage(Request $request)
    {
        $image = \App\Models\ProductImage::findOrFail($request->image_id);
        $image->delete();

        return response()->json(['success' => true]);
    }
}
