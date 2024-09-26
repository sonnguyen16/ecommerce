<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create([
            'user_id' => Auth::id(),
            'total' => $validated['total'],
            'code' => time(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'address' => $validated['address'],
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
    }

    public function getOrders()
    {
        $orders = Order::where('user_id', Auth::id())->with('orderDetails.product', 'orderDetails.order', 'orderDetails.locations')->get();

        return response()->json($orders);
    }
}
