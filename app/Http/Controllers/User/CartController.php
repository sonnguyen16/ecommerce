<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddToCartRequest;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function getCart(Request $request)
    {
        $carts = Cart::where('user_id', Auth::id())->with('product')->get();
        return response()->json($carts);
    }

    public function addToCart(AddToCartRequest $request)
    {
        $validated = $request->validated();

        $cart = Cart::where('user_id', Auth::id())
            ->where('product_id', $validated['product_id'])
            ->first();

        if ($cart) {
            $cart->update([
                'quantity' => $cart->quantity + $validated['quantity'],
            ]);

            return response()->json([
                'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
            ]);
        }

        Cart::create([
            'user_id' => Auth::id(),
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
        ]);

        return response()->json([
            'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
        ]);
    }

    public function addManyToCart(Request $request)
    {
        Auth::user()->carts()->delete();
        $carts = $request->input('carts');

        if (!$carts || !is_array($carts)) {
            return response()->json([
                'message' => 'Không có sản phẩm nào trong giỏ hàng',
            ]);
        }

        foreach ($carts as $cart) {
            // Handle both formats: direct product_id or nested product.id
            $productId = $cart['product_id'] ?? ($cart['product']['id'] ?? null);
            $quantity = $cart['quantity'] ?? 1;

            if ($productId) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $productId,
                    'quantity' => $quantity,
                ]);
            }
        }

        return response()->json([
            'message' => 'Cập nhật giỏ hàng thành công',
        ]);
    }

}
