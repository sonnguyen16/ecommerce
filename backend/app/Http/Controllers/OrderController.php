<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        try {
            $validated = $request->validated();

            $order = Order::create([
                'user_id' => Auth::id(),
                'total' => $validated['total'],
                'code' => time(),
            ]);

            $products = $validated['products'];

            foreach ($products as $product) {
                OrderDetail::create([
                    'order_id' => $order->id,
                    'product_id' => $product['product_id'],
                    'quantity' => $product['quantity'],
                    'price' => $product['price'],
                    'total' => $product['total'],
                ]);
            }

            return response()->json([
                'message' => 'Đặt hàng thành công',
            ]);
        }catch (\Exception $e) {
            return response()->json([
                'message' => 'Có lỗi xảy ra',
            ], 500);
        }
    }
}
