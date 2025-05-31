<?php

namespace App\Http\Controllers;

use Endroid\QrCode\QrCode;
use Endroid\QrCode\Writer\PngWriter;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Cart;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;
use App\Models\Product;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;



class CheckoutController extends Controller
{
    public function showQRCode()
    {
        $paymentUrl = 'http://173.16.16.158:8000/checkout/qr-confirm'; // Thay đổi URL này thành URL thực tế.

        $qrCode = new QrCode($paymentUrl);
        $qrCode->setSize(300);
        $qrCode->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);

        // encode thành base64 để hiển thị trực tiếp
        $qrBase64 = base64_encode($result->getString());

        return view('checkout.qrcode', compact('qrBase64'));
    }

    public function confirmPayment()
    {
        $user = auth()->user();
        $cart = session('cart', []);

        $province = session('province_code');
        $district = session('district_code');
        $ward = session('ward_code');

        if (empty($cart) || !$province || !$district || !$ward) {
            return redirect()->route('cart.view')->with('message', 'Vui lòng kiểm tra lại giỏ hàng và địa chỉ giao hàng.');
        }

        // 1. Tính tổng tiền
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // 2. Tạo mã đơn hàng
        $orderCode = 'ORD-' . strtoupper(uniqid());

        // 3. Lưu đơn hàng với trạng thái ĐÃ THANH TOÁN
        $order = Order::create([
            'user_id' => $user->id,
            'province_code' => $province,
            'district_code' => $district,
            'ward_code' => $ward,
            'total_price' => $total,
            'order_code' => $orderCode,
            'payment_status' => 'Đã thanh toán', 
        ]);

        // 4. Lưu từng sản phẩm
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            $product = Product::find($item['id']);
            if ($product) {
                $product->sold += $item['quantity'];
                $product->save();
            }
        }

        // 5. Gửi email xác nhận
        $order->load(['user', 'province', 'district', 'ward', 'orderItems.product']);
        Mail::to($user->email)->send(new OrderConfirmation($order));

        // 6. Xóa giỏ hàng
        session()->forget('cart');
        Cart::where('user_id', $user->id)->delete();

        // 7. Trả về trang hóa đơn
        return view('emails.orders.invoice', compact('order'));
    }



    public function paymentOptions()
    {
        $user = auth()->user();
        $cart = session('cart', []);

        $province = session('province_code');
        $district = session('district_code');
        $ward = session('ward_code');

        if (empty($cart) || !$province || !$district || !$ward) {
            return redirect()->route('cart.view')->with('message', 'Vui lòng kiểm tra lại giỏ hàng và địa chỉ giao hàng.');
        }

        // 1. Tính tổng tiền
        $total = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        // 2. Tạo mã đơn hàng
        $orderCode = 'ORD-' . strtoupper(uniqid());

        // 3. Tạo đơn hàng (chờ thanh toán)
        $order = Order::create([
            'user_id' => $user->id,
            'province_code' => $province,
            'district_code' => $district,
            'ward_code' => $ward,
            'total_price' => $total,
            'order_code' => $orderCode,
            'payment_status' => 'Chờ thanh toán', 
        ]);

        // 4. Lưu từng sản phẩm
        foreach ($cart as $item) {
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $item['id'],
                'name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
            ]);
            $product = Product::find($item['id']);
            if ($product) {
                $product->sold += $item['quantity'];
                $product->save();
            }
        }

        // 5. Tạo QR code dẫn tới xác nhận đơn này (thay đổi thành IP thật và truyền mã order_code)
        $paymentUrl = 'http://173.16.16.158:8000/checkout/qr-confirm/' . $orderCode;

        $qrCode = new QrCode($paymentUrl);
        $qrCode->setSize(300)->setMargin(10);

        $writer = new PngWriter();
        $result = $writer->write($qrCode);
        $qrBase64 = base64_encode($result->getString());

        // 6. Xóa giỏ hàng
        session()->forget('cart');
        Cart::where('user_id', $user->id)->delete();

        // 7. Trả về view thanh toán với QR
        return view('cart.payment_method', compact('qrBase64', 'orderCode', 'order'));
    }

    public function showQrConfirm($order_code)
    {
            
        $order = Order::where('order_code', $order_code)
            ->with(['orderItems.product', 'user', 'province', 'district', 'ward'])
            ->firstOrFail();
        return view('cart.qr_confirm', compact('order'));
    }
    

    // public function handleQrConfirm(Request $request, $order_code)
    // {
    //     $order = Order::where('order_code', $order_code)
    //         ->with(['orderItems.product', 'user', 'province', 'district', 'ward'])
    //         ->firstOrFail();

    //     if ($order->payment_status === 'Đã thanh toán') {
    //         return view('emails.orders.invoice', compact('order'))
    //             ->with('message', 'Đơn hàng này đã được xác nhận thanh toán!');
    //     }

    //     $order->payment_status = 'Đã thanh toán';
    //     $order->save();

    //     // Gửi mail xác nhận
    //     Mail::to($order->user->email)->send(new OrderConfirmation($order));

    //     return view('emails.orders.invoice', compact('order'))
    //         ->with('message', 'Xác nhận thanh toán thành công!');
    // }

    public function handleQrConfirm(Request $request, $order_code)
    {
        $order = Order::where('order_code', $order_code)
            ->with(['orderItems.product', 'user', 'province', 'district', 'ward'])
            ->firstOrFail();

        if ($order->payment_status === 'Đã thanh toán') {
            // Redirect về trang hóa đơn, kèm thông báo đã xác nhận trước đó
            return redirect()->route('order.invoice', $order->order_code)
                ->with('message', 'Đơn hàng này đã được xác nhận thanh toán!');
        }

        $order->payment_status = 'Đã thanh toán';
        $order->save();

        // Gửi mail xác nhận
        Mail::to($order->user->email)->send(new OrderConfirmation($order));

        // Redirect về trang hóa đơn, kèm thông báo thành công
        return redirect()->route('order.invoice', $order->order_code)
            ->with('message', 'Xác nhận thanh toán thành công!');
    }

    public function showInvoice($order_code)
    {
        $order = Order::where('order_code', $order_code)
            ->with(['orderItems.product', 'user', 'province', 'district', 'ward'])
            ->firstOrFail();
        return view('emails.orders.invoice', compact('order'));
    }

}
