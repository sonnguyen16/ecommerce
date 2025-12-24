<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PageController extends Controller
{
    protected $mediaUrl;

    public function __construct()
    {
        $this->mediaUrl = config('app.url') . '/storage/';
    }

    /**
     * Home page
     */
    public function home()
    {
        $products = Product::latest()->take(12)->get();
        $categories = Category::all();

        return Inertia::render('Home', [
            'featuredProducts' => $products->take(6)->values(),
            'newProducts' => $products->skip(6)->values(),
            'categories' => $categories,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Cart page
     */
    public function cart()
    {
        $cart = [];
        if (auth()->check()) {
            $cart = auth()->user()->carts()->with('product')->get()->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product' => $item->product,
                    'quantity' => $item->quantity,
                ];
            });
        }

        return Inertia::render('Cart', [
            'cart' => $cart,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Product detail page
     */
    public function productShow($slug)
    {
        $product = Product::where('slug', $slug)->with('images', 'category')->firstOrFail();
        $similarProducts = Product::where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(12)
            ->get();

        return Inertia::render('Products/Show', [
            'product' => $product,
            'similarProducts' => $similarProducts,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Category page
     */
    public function categoryShow($slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $categories = Category::all();
        $products = Product::where('category_id', $category->id)->paginate(12);

        return Inertia::render('Categories/Show', [
            'category' => $category,
            'categories' => $categories,
            'products' => $products,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Blog detail page
     */
    public function blogShow($slug)
    {
        $blog = Blog::where('slug', $slug)->where('is_public', true)->first();
        $relatedBlogs = [];

        if ($blog) {
            $relatedBlogs = Blog::where('id', '!=', $blog->id)
                ->where('is_public', true)
                ->take(3)
                ->get();
        }

        return Inertia::render('Blog/Show', [
            'blog' => $blog,
            'relatedBlogs' => $relatedBlogs,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Profile page
     */
    public function profile()
    {
        $user = auth()->user();
        $provinces = \App\Models\Province::all();
        $districts = [];
        $wards = [];

        // Load districts and wards if user has province/district set
        if ($user->province) {
            $districts = \App\Models\District::where('province_code', $user->province)->get();
        }
        if ($user->district) {
            $wards = \App\Models\Ward::where('district_code', $user->district)->get();
        }

        return Inertia::render('Profile/Index', [
            'mediaUrl' => $this->mediaUrl,
            'provinces' => $provinces,
            'districts' => $districts,
            'wards' => $wards,
        ]);
    }

    /**
     * Order tracking page
     */
    public function tracking(Request $request)
    {
        $status = $request->query('status');
        $userId = auth()->id();

        // Query OrderDetails directly for proper pagination (10 details per page)
        $query = \App\Models\OrderDetail::query()
            ->whereHas('order', function ($q) use ($userId) {
                $q->where('user_id', $userId);
            })
            ->with(['product', 'order'])
            ->orderBy('created_at', 'desc');

        // Filter by status if provided
        if ($status && $status !== 'all') {
            $query->where('status', $status);
        }

        $orderDetails = $query->paginate(10)->withQueryString();

        return Inertia::render('Profile/Tracking', [
            'orderDetails' => $orderDetails,
            'mediaUrl' => $this->mediaUrl,
            'currentStatus' => $status ?? 'all',
        ]);
    }

    /**
     * Order detail page
     */
    public function orderDetail($id)
    {
        $order = auth()->user()->orders()
            ->with(['orderDetails.product', 'orderDetails.locations', 'province', 'district', 'ward'])
            ->findOrFail($id);

        return Inertia::render('Profile/OrderDetail', [
            'order' => $order,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }
}
