<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminOrderController extends Controller
{
    protected $mediaUrl;

    public function __construct()
    {
        $this->mediaUrl = config('app.url') . '/storage/';
    }

    /**
     * Display orders list
     */
    public function index()
    {
        $total = OrderDetail::count();
        $totalMoney = OrderDetail::sum('total');
        $orders = OrderDetail::with(['order', 'product'])->latest()->paginate(20);

        return Inertia::render('Admin/Orders/Index', [
            'total' => $total,
            'total_money' => $totalMoney,
            'orders' => $orders,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Show order detail
     */
    public function show($id)
    {
        $orderDetail = OrderDetail::with(['order.province', 'order.district', 'order.ward', 'product', 'locations'])->findOrFail($id);

        return Inertia::render('Admin/Orders/Show', [
            'order' => $orderDetail->order,
            'orderDetail' => $orderDetail,
            'mediaUrl' => $this->mediaUrl,
        ]);
    }

    /**
     * Update order status
     */
    public function update(Request $request, $id)
    {
        $order = OrderDetail::findOrFail($id);
        $order->update($request->only(['status']));

        return redirect()->back()->with('success', 'Cập nhật đơn hàng thành công');
    }
}
