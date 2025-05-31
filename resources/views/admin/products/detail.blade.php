@extends('layouts.admin')
@section('title')
    product-detail
@endsection

@section('content')
<div class="container detail-container">
    <div class="detail-title">Chi tiết sản phẩm</div>
    <div class="detail-box">
        <div class="detail-info">
            <div><span class="tua">ID:</span> {{$item->id}}</div>
            <div><span class="tua">Name:</span> {{$item->name}}</div>
            <div><span class="tua">Brand:</span> {{$item->brand}}</div>
            <div><span class="tua">Price:</span> {{ number_format($item->price, 0, ',', '.') }} đ</div>
        </div>
        <div class="detail-image">
            <span class="tua">Image:</span><br>
            <img src="{{ asset('image/products/' . $item->image) }}" alt="{{ $item->name }}">
        </div>
    </div>
</div>
<style>
.detail-container {
    background-color: #fdf7f7;
    padding: 30px 30px 40px 30px;
    border-radius: 14px;
    box-shadow: 0 8px 32px rgba(44, 62, 80, 0.07);
    margin-top: 30px;
    max-width: 800px;
}
.detail-title {
    text-align: center;
    color: #d63384;
    font-size: 28px;
    font-weight: 700;
    margin-bottom: 24px;
    letter-spacing: 1px;
}
.detail-box {
    display: flex;
    gap: 32px;
    flex-wrap: wrap;
    align-items: flex-start;
    justify-content: space-between;
}
.detail-info {
    flex: 1.2;
    font-size: 19px;
    line-height: 2.0;
}
.detail-info .tua {
    font-weight: bold;
    color: #6f42c1;
    width: 90px;
    display: inline-block;
}
.detail-image {
    flex: 1;
    text-align: center;
}
.detail-image .tua {
    color: #2d3748;
    font-size: 16px;
    font-weight: 500;
}
.detail-image img {
    margin-top: 8px;
    width: 100%;
    max-width: 300px;
    border-radius: 8px;
    border: 1.5px solid #eee;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    background: #fff;
}
@media (max-width: 768px) {
    .detail-box {
        flex-direction: column;
        gap: 20px;
    }
    .detail-image img {
        max-width: 80vw;
    }
}
</style>
@endsection
