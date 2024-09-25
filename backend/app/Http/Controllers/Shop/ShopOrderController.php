<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
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
            })->with(['product', 'order.user', 'locations'])->orderBy('created_at', 'desc');

        $total_money = $orders->sum('total');

        return response()->json([
            'orders' => $orders->paginate(10),
            'total_money' => $total_money
        ]);
    }
}
