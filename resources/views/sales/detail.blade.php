@extends('layouts.admin')
@section('title', 'Sale Product Detail')

@section('content')
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card position-relative">
                    <img src="{{ asset('images/' . $item->picture) }}" class="card-img-top" alt="{{ $item->name }}">
                    <div class="discount-badge position-absolute top-0 end-0 bg-danger text-white px-3 py-2">
                        {{ $item->discount_percent }}% OFF
                    </div>
                    <div class="card-body">
                        <h4 class="card-title">{{ $item->name }}</h4>
                        <p class="card-text">Brand: <strong>{{ $item->brand }}</strong></p>
                        <p class="card-text">Original Price: <s>{{ number_format($item->price) }} đ</s></p>
                        <p class="card-text text-danger">
                            Sale Price:
                            <strong>{{ number_format($item->price * (1 - $item->discount_percent / 100)) }} đ</strong>
                        </p>
                        <a href="{{ url('/sales') }}" class="btn btn-secondary mt-2">Back to List</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .discount-badge {
            font-weight: bold;
            font-size: 1rem;
            border-radius: 0 0 0 8px;
        }
    </style>
@endsection
