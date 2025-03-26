<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreProductRequest;
use App\Models\ProductImage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use Illuminate\Support\Str;

class ShopProductController extends Controller
{
    public function getProducts(Request $request): JsonResponse
    {
        $shop_id = Auth::user()->shop->id;

        $products = Product::query()
            ->where('shop_id', $shop_id)
            ->whereNull('deleted_at')
            ->whereHas('category', function($query) {
                $query->whereNull('deleted_at');
            })
            ->with(['category','images'])
            ->orderBy('created_at', 'desc');

        return response()->json($products->paginate(6));
    }

    public function getProduct($product_id)
    {
        $shop_id = Auth::user()->shop->id;

        $product = Product::query()
            ->where('id', $product_id)
            ->where('shop_id', $shop_id)
            ->whereNull('deleted_at')
            ->with(['category','images'])
            ->first();

        return response()->json($product);
    }

    public function storeProduct(StoreProductRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $validated['shop_id'] = Auth::user()->shop->id;
        $validated['slug'] = Str::slug($validated['name']);

        if($validated['seo_url'] === '') {
            $validated['seo_url'] = Str::slug($validated['name']);
        }

        if($validated['seo_title'] === '') {
            $validated['seo_title'] = $validated['name'];
        }

        if($validated['seo_description'] === '') {
            $validated['seo_description'] = $validated['description'];
        }

        if(!is_null($validated['attributes'])) {
            $lines = explode("\n", trim($validated['attributes']));
            $result = [];
            foreach ($lines as $line) {
                if (trim($line) === '') {
                    continue;
                }
                list($key, $value) = explode(':', $line, 2);
                $result[trim($key)] = trim($value);
            }
            $validated['attributes'] = json_encode($result, JSON_PRETTY_PRINT);
        }

        if($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $validated['thumbnail']->store('products', 'public');
        }

        $product = Product::updateOrCreate(['id' => $validated['id']], $validated);

        if($request->hasFile('images')) {
            $images = [];
            foreach ($request->file('images') as $image) {
                $images[] = $image->store('products', 'public');
            }
            $product->images()->createMany(array_map(function ($image) {
                return ['path' => $image];
            }, $images));
        }

        return response()->json(['message' => 'Thêm sản phẩm thành công', $request->all()]);
    }

    public function deleteImage(Request $request): JsonResponse
    {
        $image = ProductImage::find($request->image_id);
        $image->delete();
        return response()->json(['message' => 'Xóa ảnh thành công']);
    }

    public function deleteProduct($product_id): JsonResponse
    {
        $shop_id = Auth::user()->shop->id;

        $product = Product::query()
            ->where('id', $product_id)
            ->where('shop_id', $shop_id)
            ->first();

        if (!$product) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Xóa sản phẩm thành công']);
    }
}
