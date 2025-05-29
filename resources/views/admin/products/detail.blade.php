@extends('layouts.admin')
@section('title')
    product-detail
@endsection

@section('content')
    <div class="container">
        <h3>Chi tiết sản phẩm</h3>
        <hr>

        <span class="tua">ID: </span> {{$item->id}} <br>
        <span class="tua">Name: </span> {{$item->name}} <br>
        <span class="tua">Brand: </span> {{$item->brand}} <br>
        <p><span class="tua">Price:</span> {{ number_format($item->price, 0, ',', '.') }} đ</p>
        <span class="tua">Image: </span>
        <img src="{{asset("/images/$item->picture")}}" alt="{{$item->name}}"><br><br>
    </div>
    <style>
        .detail-container {
            background-color: #fdf7f7;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
            margin-top: 20px;
        }
    
        .detail-title {
            text-align: center;
            color: #d63384;
            font-size: 28px;
            font-weight: 600;
            margin-bottom: 30px;
        }
    
        .detail-box {
            display: flex;
            flex-wrap: wrap;
            gap: 30px;
            align-items: flex-start;
        }
    
        .detail-info {
            flex: 1;
            font-size: 18px;
        }
    
        .detail-info .tua {
            font-weight: bold;
            color: #6f42c1;
            display: inline-block;
            width: 100px;
        }
    
        .detail-image {
            flex: 1;
            text-align: center;
        }
    
        .detail-image img {
            width: 100%;
            max-width: 350px;
            border-radius: 8px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
@endsection