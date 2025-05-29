@extends('layouts.admin')

@section('title', 'Create Hot Product')

@section('content')
    <h2 class="title">Thêm Sản Phẩm HOT</h2>
    <hr>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    {{-- Hiển thị lỗi validate --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('hots.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-3">
            <label for="name" class="form-label">Tên sản phẩm</label>
            <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="mb-3">
            <label for="brand" class="form-label">Thương hiệu</label>
            <input type="text" id="brand" name="brand" class="form-control" value="{{ old('brand') }}" required>
        </div>

        <div class="mb-3">
            <label for="price" class="form-label">Giá</label>
            <input type="number" id="price" name="price" class="form-control" value="{{ old('price') }}"
                min="0" max="5000000" required>
        </div>

        <div class="mb-3">
            <label for="picture" class="form-label">Ảnh sản phẩm</label>
            <input type="file" id="picture" name="picture" class="form-control" accept=".jpg,.jpeg,.png,.webp"
                required>
        </div>

        <button type="submit" class="btn btn-primary">Thêm sản phẩm</button>
        <button type="reset" class="btn btn-secondary"> Làm Mới</button>
    </form>

    <style>
        .title {
            text-align: center;
            margin-bottom: 20px;
            color: #dc3545;
            /* màu đỏ nổi bật cho "HOT" */
        }

        form {
            max-width: 600px;
            margin: 0 auto;
            /* background: #f9f9f9; */
            padding: 20px;
            border-radius: 10px;
            /* box-shadow: 0 0 10px #ccc; */
        }
    </style>
@endsection
