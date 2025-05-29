@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h3 class="text-center mb-4">💳 Chọn phương thức thanh toán</h3>
    <div class="row">
        <!-- QR -->
        <div class="col-md-6 text-center border-end">
            <h5>📱 Quét mã QR</h5>
            <img src="data:image/png;base64,{{ $qrBase64 }}" width="300" alt="QR Code">
            <p class="mt-2">Quét QR Code để thanh toán</p>
        </div>

        <!-- Cash -->
        <div class="col-md-6 text-center">
            <h5>💵 Thanh toán khi nhận hàng</h5>
            <form action="{{ route('cart.completeOrder') }}" method="POST" class="mt-3">
                @csrf
                <button class="btn btn-success px-4 py-2">Xác nhận thanh toán</button>
            </form>
        </div>
    </div>
</div>
@endsection
