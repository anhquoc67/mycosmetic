@extends('layouts.app')

@section('content')
<div class="container">
    <h2 class="mb-4 text-danger">🔥 Sản Phẩm Khuyến Mãi</h2>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 ">
                    <img src="{{ asset('image/products/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: contain;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        @if ($product->discount_percent && $product->discount_percent > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2" style="z-index:10;">
                                SALE -{{ $product->discount_percent }}%
                            </span>
                            <p class="card-text mb-1">
                                <span style="text-decoration: line-through; color: #888;">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </span>
                                <span style="font-size: 1.1em; color: #e74c3c; margin-left: 10px; font-weight:bold;">
                                    {{ number_format($product->price * (1 - $product->discount_percent/100), 0, ',', '.') }}₫
                                </span>
                            </p>
                            <p class="card-text text-danger mb-1"><strong>Giảm giá: {{ $product->discount_percent }}%</strong></p>
                        @endif
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text"><strong>Thương hiệu:</strong> {{ $product->brand }}</p>
                        <p class="card-text"><strong>Đã bán:</strong> {{ $product->sold }} lượt</p>
                        <p class="card-text"><small>Mã SP: {{ $product->id }}</small></p>
                        <form class="add-to-cart-form" data-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="btn btn-sm btn-primary mt-2 add-to-cart-btn">🛒 Thêm vào giỏ</button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
