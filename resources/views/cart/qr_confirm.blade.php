@extends('layouts.app')
@section('content')
<div class="container mt-5 text-center">
    <h3 class="mb-4">Xác nhận thanh toán cho đơn hàng: {{ $order->order_code }}</h3>
    <p>Khách hàng: <strong>{{ $order->user->name }}</strong></p>
    <p>Tổng tiền: <strong>{{ number_format($order->total_price, 0, ',', '.') }}₫</strong></p>
    <p>Trạng thái: <b>{{ $order->payment_status }}</b></p>
    <form method="POST" action="{{ route('checkout.qrConfirm.post', $order->order_code) }}">
        @csrf
        <button type="submit" class="btn btn-success px-4 py-2">Tôi đã thanh toán</button>
    </form>
</div>
@endsection
