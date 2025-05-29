<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Order;
use App\Models\User;
use App\Models\OrderItem;


class UserController extends Controller
{
    public function account()
    {
        $user = auth()->user();

        $province = Province::where('code', $user->province_code)->value('name');
        $district = District::where('code', $user->district_code)->value('name');
        $ward = Ward::where('code', $user->ward_code)->value('name');

        return view('user.account', compact('user', 'province', 'district', 'ward'));
    }

    public function orders()
    {
        $orders = auth()->user()->orders()->with('orderItems.product')->latest()->get();
        return view('user.orders', compact('orders'));
    }

    public function orderHistory()
    {
        $orders = Order::where('user_id', auth()->id())->latest()->get();
        return view('user.orders', compact('orders'));
    }

    public function viewOrder($id)
{
    // Lấy đơn hàng theo ID của user đã đăng nhập
    $order = Order::with(['orderItems.product', 'user'])->where('user_id', auth()->id())->findOrFail($id);

    // Lấy tên địa chỉ
    $province = Province::where('code', $order->province_code)->value('name');
    $district = District::where('code', $order->district_code)->value('name');
    $ward = Ward::where('code', $order->ward_code)->value('name');

    return view('user.order_detail', compact('order', 'province', 'district', 'ward'));
}
}
