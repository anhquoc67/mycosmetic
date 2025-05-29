@extends('layouts.admin')

@section('title')
    Hot Products
@endsection

@section('content')
    <h2 class="title">Danh Sách Sản Phẩm HOT</h2>
    <hr>

    <div class="mb-3 mt-3">
        <a href="{{ url('hots/create') }}" class="btn btn-danger create-btn">Create New HOT</a>
    </div>

    <div class="mb-3 mt-3">
        <form action="">
            <input type="text" name="sname" placeholder="Search product name..." value="{{ request('sname') }}">
            <button class="btn btn-dark">Search</button>
        </form>
    </div>

    <div class="row">
        @foreach ($list as $item)
            <div class="col-md-3 mb-4">
                <div class="card product-card">
                    <div class="position-relative">
                        <img src="{{ asset('images/' . $item->picture) }}" class="card-img-top product-img" alt="Product Image">
                        <span class="hot-badge">HOT</span>
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title">{{ $item->name }}</h5>
                        <p class="card-text">{{ $item->brand }}</p>
                        <p class="card-text text-danger">{{ number_format($item->price, 0, ',', '.') }} đ</p>
                        <a href="{{ url('/hots/detail/' . $item->id) }}" class="btn btn-info btn-sm">Detail</a>
                        <a href="{{ url('/hots/edit/' . $item->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <a href="{{ url('/hots/delete/' . $item->id) }}" onclick="return confirm('Are you sure?')" class="btn btn-danger btn-sm">Delete</a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <style>
        h2.title {
            text-align: center;
            margin-bottom: 20px;
        }

        .create-btn {
            font-weight: bold;
            border-radius: 8px;
        }

        .product-img {
            height: 200px;
            object-fit: cover;
            border-top-left-radius: 0.5rem;
            border-top-right-radius: 0.5rem;
        }

        .hot-badge {
            position: absolute;
            top: 8px;
            right: 8px;
            background-color: red;
            color: white;
            padding: 5px 10px;
            font-weight: bold;
            font-size: 12px;
            border-radius: 4px;
        }

        .product-card {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-radius: 10px;
        }

        input[name="sname"] {
            width: 250px;
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            margin-right: 10px;
        }
    </style>
@endsection
