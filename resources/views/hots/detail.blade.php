@extends('layouts.admin')
@section('title')
    Chi Tiết Sản Phẩm HOT
@endsection

@section('content')
    <h2 class="title">Chi Tiết Sản Phẩm HOT</h2>
    <hr>

    <div class="card product-detail mx-auto" style="max-width: 500px;">
        <div class="card-body text-center">
            <div class="image-container position-relative mb-3">
                @if($item->picture)
                    <img src="{{ asset('images/' . $item->picture) }}" class="img-fluid rounded shadow">
                    <span class="hot-badge">HOT</span>
                @else
                    <p>Không có hình ảnh</p>
                @endif
            </div>

            <h4 class="card-title">{{ $item->name }}</h4>
            <p class="card-text"><strong>Thương hiệu:</strong> {{ $item->brand }}</p>
            <p class="card-text"><strong>Giá:</strong> {{ number_format($item->price, 0, ',', '.') }} đ</p>

            <a href="{{ url('/hots') }}" class="btn btn-secondary mt-3">Quay lại danh sách</a>
        </div>
    </div>

    <style>
        .title {
            text-align: center;
            font-weight: bold;
            margin-bottom: 25px;
        }

        .image-container {
            position: relative;
            display: inline-block;
        }

        .hot-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            background: red;
            color: white;
            padding: 4px 10px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
        }

        .product-detail {
            background-color: #fff;
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
@endsection
