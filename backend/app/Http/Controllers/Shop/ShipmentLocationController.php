<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentLocationRequest;
use App\Models\OrderDetail;
use App\Models\ShipmentLocation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShipmentLocationController extends Controller
{
    public function store(StoreShipmentLocationRequest $request)
    {
        ShipmentLocation::create($request->validated());

        $orderDetail = OrderDetail::find($request->order_detail_id);
        $orderDetail->status = $request->status;
        $orderDetail->save();

        return response()->json($this->getOrder($orderDetail->id));
    }

    public function getOrder($order_detail_id)
    {
        $shop_id = Auth::user()->shop->id;

        $order = OrderDetail::query()
            ->where('id', $order_detail_id)
            ->whereHas('product', function ($query) use ($shop_id) {
                $query->where('shop_id', $shop_id);
            })->with(['product', 'order', 'locations'])->first();

        return $order;
    }
}
