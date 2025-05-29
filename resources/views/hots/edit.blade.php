@extends('layouts.admin')
@section('title')
    Edit Hot Product
@endsection

@section('content')
    <h2 class="title">Chỉnh Sửa Sản Phẩm HOT</h2>
    <hr>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Oops!</strong> Vui lòng kiểm tra lại:
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ url('hots/update/' . $item->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Tên sản phẩm:</label>
            <input type="text" name="name" id="name" value="{{ old('name', $item->name) }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="brand">Thương hiệu:</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand', $item->brand) }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label for="price">Giá:</label>
            <input type="number" name="price" id="price" value="{{ old('price', $item->price) }}" class="form-control" required>
        </div>

        <div class="form-group mb-3">
            <label>Hình ảnh hiện tại:</label><br>
            @if($item->picture)
                <img src="{{ asset('images/' . $item->picture) }}" width="150" class="img-thumbnail mb-2">
            @else
                <p>Chưa có hình ảnh</p>
            @endif
        </div>

        <div class="form-group mb-3">
            <label for="picture">Chọn hình mới (nếu muốn):</label>
            <input type="file" name="picture" id="picture" class="form-control">
        </div>

        <button type="submit" class="btn btn-primary">Cập nhật</button>
        <a href="{{ url('/hots') }}" class="btn btn-secondary">Quay lại</a>
    </form>

    <style>
        .title {
            text-align: center;
            font-weight: 600;
            font-size: 22px;
            margin-bottom: 20px;
        }

        .form-group label {
            font-weight: bold;
        }

        .btn {
            margin-right: 10px;
        }
    </style>
@endsection
