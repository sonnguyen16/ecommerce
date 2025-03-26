<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\OrderRequest;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function order(OrderRequest $request)
    {
        $validated = $request->validated();

        $order = Order::create([
            'total' => 0,
            'user_id' => Auth::id(),
            'code' => time(),
            'name' => $validated['name'],
            'phone' => $validated['phone'],
            'province' => $validated['province'],
            'district' => $validated['district'],
            'ward' => $validated['ward'],
            'address' => $validated['address'],
        ]);

        $products = $validated['products'];
        $total = 0;

        foreach ($products as $product) {
            $product_found = Product::find($product['product_id']);
            OrderDetail::create([
                'order_id' => $order->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity'],
                'price' => $product_found->sale_price,
                'total' => $product_found->sale_price * $product['quantity'],
            ]);
            $total += $product_found->sale_price * $product['quantity'];
        }

        $order->total = $total;
        $order->save();

        return response()->json([
            'message' => 'Đặt hàng thành công',
        ]);
    }

    public function getOrders(Request $request)
    {
        $orders = OrderDetail::query()
            ->whereHas('order', function ($query){
                $query->where('user_id', Auth::id());
            })->with(['product', 'order'])->orderBy('created_at', 'desc');

        if($request->filled('status') && $request->status != 'all') {
            $orders->where('status', $request->status);
        }

        return response()->json($orders->paginate(5));
    }

    public function getOrder($order_detail_id)
    {
        $order = OrderDetail::query()
            ->where('id', $order_detail_id)
            ->whereHas('order', function ($query) {
                $query->where('user_id', Auth::id());
            })->with(['product', 'order', 'locations', 'order.province', 'order.district', 'order.ward'])->first();

        return response()->json($order);
    }

    public function listOrder()
    {
        $orders = Order::where('user_id', Auth::id())
            ->with(['orderDetails',
            'orderDetails.product',
            'orderDetails.product.images',
            'orderDetails.locations',
            'province',
            'district',
            'ward'])
            ->orderBy('created_at', 'desc')->get();

        return response()->json($orders);
    }
}
