@extends('layouts.admin')
@section('title')
    products
@endsection

@section('content')
    <h2 class="title">Danh Sách Sản Phẩm Sale</h2>
    <hr>
    <div class="mt-3 mb-3">
        <a href="{{ url('sales/create') }}" class="btn btn-success mx-2 create-btn">Create New Sale</a>
    </div>
    <div class="mt-3 mb-3">
        <form action="">
            <input type="text" name="sname" id="sname" placeholder="enter product name" value="{{ request('sname') }}">
            <button class="submit btn btn-dark">Search</button>
        </form>
    </div>
    <table class="table table-hover table-striped">
        <tbody>
            <div class="row">
                @foreach ($list as $item)
                    <div class="col-md-4 mb-4">
                        <div class="card position-relative">
                            <img src="{{ asset('images/' . $item->picture) }}" class="card-img-top"
                                alt="{{ $item->name }}">
                            <div class="discount-badge position-absolute top-0 end-0 bg-danger text-white px-2 py-1">
                                {{ $item->discount_percent }}% OFF
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ $item->name }}</h5>
                                <p class="card-text">Brand: {{ $item->brand }}</p>
                                <p class="card-text text-muted"><s>{{ number_format($item->price) }} đ</s></p>
                                <p class="card-text text-danger">
                                    Sale Price: {{ number_format($item->price * (1 - $item->discount_percent / 100)) }} đ
                                </p>
                                <a href="{{ url("/sales/detail/$item->id") }}" class="btn btn-info btn-sm">Detail</a>
                                <a href="{{ url("/sales/edit/$item->id") }}" class="btn btn-warning btn-sm">Edit</a>
                                <a href="{{ url("/sales/delete/$item->id") }}" class="btn btn-danger btn-sm"
                                    onclick="return yesno()">Delete</a>
                            </div>
                        </div>
                    </div>
                    @if ($list->isEmpty())
                        <div class="alert alert-warning mt-4">
                            Không có sản phẩm nào được tìm thấy.
                        </div>
                    @endif
                @endforeach
            </div>
        </tbody>
    </table>
    <style>
        h2 {
            text-align: center;
        }

        .create-btn {
            width: 250px;
            font-weight: bold;
            border-radius: 8px;
        }

        .discount-badge {
            font-weight: bold;
            border-radius: 0 0 0 8px;
        }
    </style>
@endsection

@section('script')
    <script>
        function yesno() {
            return confirm("Are you sure you want to delete this product?");
        }
    </script>
@endsection
