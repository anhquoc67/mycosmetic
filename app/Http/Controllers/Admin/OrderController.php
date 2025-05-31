<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Http\Middleware\IsAdmin;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;

class OrderController extends Controller
{
    // hàm hiển thị tất cả đơn hàng của người dùng
    public function index(User $user)
    {
        $orders = $user->orders()->with('items')->paginate(10); // Gọi quan hệ trong model User
        return view('admin.orders.vieworders', compact('user', 'orders'));
    }
    public function show(Order $order)
    {
        $order->load('user', 'items');

        // Lấy thông tin địa chỉ giao hàng
        $province = Province::where('code', $order->province_code)->value('name');
        $district = District::where('code', $order->district_code)->value('name');
        $ward = Ward::where('code', $order->ward_code)->value('name');
        return view('admin.orders.showOrders', compact('order', 'province', 'district', 'ward'));
    }

    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);
        $currentStatus = $order->delivery_status;
        $newStatus = $request->delivery_status;

        // Mảng quy định các trạng thái được chuyển tiếp
        $allowed = [
            'Đã đặt hàng'   => ['Đang giao hàng'],
            'Đang giao hàng' => ['Đã giao xong'],
            'Đã giao xong' => [] // Không chuyển tiếp nữa
        ];

        // Nếu không nằm trong mảng allowed => báo lỗi
        if (!isset($allowed[$currentStatus]) || !in_array($newStatus, $allowed[$currentStatus])) {
            return redirect()->back()->with('error', 'Không thể chuyển trạng thái này!');
        }

        $order->delivery_status = $newStatus;
        $order->save();

        return redirect()->back()->with('success', 'Cập nhật trạng thái thành công!');
    }


    public function markAsPaid($id)
    {
        $order = Order::findOrFail($id);
        $order->payment_status = 'Đã thanh toán';
        $order->save();

        return back()->with('message', 'Cập nhật trạng thái thanh toán thành công!');
    }
    
}
