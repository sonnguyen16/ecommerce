<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopOrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $shop_id = Auth::user()->shop->id;

        $orders = OrderDetail::query()
            ->whereHas('product', function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            })->with(['product', 'order', 'locations'])->orderBy('created_at', 'desc');

        $total_money = $orders->sum('total');
        $total = $orders->count();

        return response()->json([
            'total' => $total,
            'orders' => $orders->paginate(10),
            'total_money' => $total_money
        ]);
    }

    public function getOrder($order_detail_id)
    {
        $shop_id = Auth::user()->shop->id;

        $order = OrderDetail::query()
            ->where('id', $order_detail_id)
            ->whereHas('product', function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            })->with(['product', 'order', 'locations', 'order.province', 'order.district', 'order.ward'])->first();

        return response()->json($order);
    }

    public function listOrder()
    {
        $shop_id = Auth::user()->shop->id;

        $orders = Order::whereHas('orderDetails', function ($query) use ($shop_id) {
            $query->whereHas('product', function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            });
        })->with(['orderDetails',
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
