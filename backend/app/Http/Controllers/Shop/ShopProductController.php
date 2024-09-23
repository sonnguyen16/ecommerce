<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;

class ShopProductController extends Controller
{
    public function getProducts(Request $request)
    {
        $shop_id = Auth::user()->shop->id;

        $products = Product::query()
            ->where('shop_id', $shop_id)
            ->with('category')
            ->orderBy('created_at', 'desc');

        return response()->json($products->paginate(6));
    }
}
