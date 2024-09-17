<?php

namespace App\Http\Controllers;

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
        try {
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
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra',
            ], 500);
        }
    }

    public function addManyToCart(Request $request)
    {
        try {
            Auth::user()->carts()->delete();
            $carts = $request->input('carts');
            foreach ($carts as $cart) {
                Cart::create([
                    'user_id' => Auth::id(),
                    'product_id' => $cart['product_id'],
                    'quantity' => $cart['quantity'],
                ]);
            }

            return response()->json([
                'message' => 'Thêm sản phẩm vào giỏ hàng thành công',
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra',
            ], 500);
        }
    }

}
