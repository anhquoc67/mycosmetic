<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Province;
use App\Models\District;
use App\Models\Ward;
use App\Models\Location;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\Mail;
use App\Mail\OrderConfirmation;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $product = Product::findOrFail($request->id);
        $quantity = $request->quantity ?? 1;

        // TÍNH GIÁ ĐÃ GIẢM (nếu có giảm giá)
        $finalPrice = $product->price;
        if ($product->discount_percent && $product->discount_percent > 0) {
            $finalPrice = $product->price * (1 - $product->discount_percent / 100);
        }

        $cart = session('cart', []);
        if (isset($cart[$product->id])) {
        $cart[$product->id]['quantity'] += $quantity;
    } else {
        $cart[$product->id] = [
            'id' => $product->id,
            'name' => $product->name,
            'price' => $finalPrice,
            'price_goc' => $product->price,
            'discount_percent' => $product->discount_percent,
            'image' => $product->image,
            'quantity' => $quantity,
        ];
    }
    session(['cart' => $cart]);
    // Đếm tổng số sản phẩm hiện tại trong giỏ
        $cartCount = collect($cart)->sum('quantity');
        return response()->json([
            'success' => true,
            'cart_count' => $cartCount,
            'message' => 'Đã thêm vào giỏ hàng!'
        ]);
        // return redirect()->route('cart.view')->with('success', 'Đã thêm vào giỏ hàng!');
    }



    public function view()
    {
        if (auth()->check()) {
            $user = auth()->user();

            // Nếu chưa có session cart → lấy từ DB
            if (!session()->has('cart')) {
                $cartItems = Cart::where('user_id', $user->id)->get();
                $cart = [];

                foreach ($cartItems as $item) {
                    $product = $item->product;
                    $cart[$product->id] = [
                        'id' => $product->id,
                        'name' => $product->name,
                        'price' => $product->price,
                        'image' => $product->image,
                        'quantity' => $item->quantity
                    ];
                }

                session()->put('cart', $cart);
            }

            // Lấy địa chỉ đã lưu
            session([
                'province_code' => $user->province_code,
                'district_code' => $user->district_code,
                'ward_code' => $user->ward_code,
            ]);
        }

        $cart = session()->get('cart', []);
        $provinces = Province::all();

        return view('cart.view', compact('cart', 'provinces'));
    }


    public function saveLocation(Request $request)
    {
        if (!auth()->check()) {
            return redirect()->route('register')->with('warning', 'Bạn cần đăng ký và đăng nhập để lưu địa chỉ giao hàng.');
        }
        
            $user = auth()->user();
            $user->province_code = $request->province_code;
            $user->district_code = $request->district_code;
            $user->ward_code = $request->ward_code;
            $user->save();       
    return redirect()->back()->with('message', 'Đã lưu địa chỉ giao hàng!');
    }

    public function remove($id)
    {
    $cart = session()->get('cart', []);
    if (isset($cart[$id])) {
        unset($cart[$id]);
        if (auth()->check()) {
        Cart::where('user_id', auth()->id())
        ->where('product_id', $id)
        ->delete();
        }

        session()->put('cart', $cart);
    }

    return redirect()->back()->with('message', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

public function update(Request $request, $id)
{

    $quantity = (int) $request->quantity;

    if ($quantity < 1 || $quantity > 10) {
        return response()->json([
            'error' => 'Số lượng không hợp lệ (phải từ 1 đến 10)',
        ], 400);
    }
    
    $cart = session()->get('cart', []);

    if (isset($cart[$id])) {
        $cart[$id]['quantity'] = max(1, (int)$request->quantity);
        session()->put('cart', $cart);

        // Trả JSON nếu là AJAX
        $itemTotal = $cart[$id]['price'] * $cart[$id]['quantity'];
        $grandTotal = collect($cart)->sum(fn($item) => $item['price'] * $item['quantity']);

        return response()->json([
            'item_total' => number_format($itemTotal, 0, ',', '.') . 'đ',
            'grand_total' => number_format($grandTotal, 0, ',', '.') . 'đ'
        ]);
    }

    return response()->json(['error' => 'Không tìm thấy sản phẩm'], 404);
}

public function checkout()
{
    if (!auth()->check()) {
        return redirect()->route('login')->with('message', 'Bạn cần đăng nhập để xác nhận đơn hàng.');
    }

    return view('cart.checkout'); 
}

public function completeOrder(Request $request)
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

    // 2. Lưu đơn hàng
    $orderCode = 'ORD-' . strtoupper(uniqid());
    $order = Order::create([
    'user_id' => $user->id,
    'province_code' => $province,
    'district_code' => $district,
    'ward_code' => $ward,
    'total_price' => $total,
    'order_code' => $orderCode,
    ]);

    // 3. Lưu từng sản phẩm
    foreach ($cart as $item) {
        OrderItem::create([
            'order_id' => $order->id,
            'product_id' => $item['id'],
            'name' => $item['name'],
            'quantity' => $item['quantity'],
            'price' => $item['price'],
        ]);

        // Cập nhật lượt bán cho sản phẩm
        $product = Product::find($item['id']);
        if ($product) {
            $product->sold += $item['quantity'];
            $product->save();
        }
    }

    // 4. Gửi email xác nhận đơn hàng
    $order->load(['user', 'province', 'district', 'ward', 'orderItems.product']);
    Mail::to($user->email)->send(new OrderConfirmation($order));

    // 5. Xoá giỏ hàng
    session()->forget('cart');

    // 6. Hiển thị trang hóa đơn
    Cart::where('user_id', $user->id)->delete();
    return view('emails.orders.invoice', compact('order'));

}

}
