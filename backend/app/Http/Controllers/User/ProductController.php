<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $limit = $request->input('limit');
        $categoryId = $request->input('category_id');
        $page = $request->input('page', 1);
        $orderBy = $request->input('orderBy', 'id');
        $orderDirection = $request->input('orderDirection', 'desc');

        $query = Product::with('images', 'category');

        if ($categoryId) {
            $query->where('category_id', $categoryId);
        }

        // Sắp xếp dữ liệu
        $query->orderBy($orderBy, $orderDirection);

        // Nếu có limit thì lấy số lượng cố định không phân trang
        if ($limit) {
            $products = $query->limit($limit)->get();
            return response()->json($products);
        }

        // Nếu không có limit thì sử dụng phân trang
        $products = $query->paginate($perPage);

        return response()->json($products);
    }

    // Thêm phương thức để lấy sản phẩm theo slug
    public function getProductBySlug($slug)
    {
        $product = Product::with(['images', 'category'])
            ->where('slug', $slug)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        return response()->json($product);
    }
}
