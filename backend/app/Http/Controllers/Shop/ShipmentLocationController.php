<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreShipmentLocationRequest;
use App\Models\OrderDetail;
use App\Models\ShipmentLocation;

class ShipmentLocationController extends Controller
{
    public function store(StoreShipmentLocationRequest $request)
    {
        $shipmentLocation = ShipmentLocation::create($request->validated());

        $orderDetail = OrderDetail::find($request->order_detail_id);
        $orderDetail->status = $request->status;
        $orderDetail->save();

        return response()->json($shipmentLocation, 201);
    }
}
