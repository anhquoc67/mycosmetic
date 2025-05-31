@extends('layouts.admin')
@section('title')
    Edit Product
@endsection

@section('content')
<div class="container edit-container">
    <h3 class="edit-title">Chỉnh sửa sản phẩm</h3>
    <hr>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.update', $item->id) }}" method="POST" enctype="multipart/form-data">
        @if ($item->deleted_at)
            <div class="alert alert-warning">
                ⚠️ Sản phẩm này hiện đang bị ẩn. Bạn có thể chỉnh sửa, nhưng người dùng chỉ thấy dữ liệu mới khi sản phẩm được khôi phục.
            </div>
        @endif
        @csrf
        <div class="form-group">
            <label for="name">Tên sản phẩm</label>
            <input type="text" class="form-control" name="name" id="name" value="{{ $item->name }}" required>
        </div>

        <div class="form-group">
            <label for="brand">Thương hiệu</label>
            <input type="text" class="form-control" name="brand" id="brand" value="{{ $item->brand }}" required>
        </div>

        <div class="form-group">
            <label for="price">Giá</label>
            <input type="number" class="form-control" name="price" id="price" value="{{ $item->price }}" required>
        </div>

        <div class="form-group">
            <label for="discount_percent">Giảm giá (%)</label>
            <input type="number" class="form-control" name="discount_percent" id="discount_percent"
                value="{{ old('discount_percent', $item->discount_percent) }}" min="0" max="100">
        </div>


        <div class="form-group">
            <label>Hình hiện tại:</label><br>
            <img src="{{ asset('image/products/' . $item->image) }}" alt="{{ $item->name }}" class="preview-img"><br><br>
        </div>

        <div class="form-group">
            <label for="image">Chọn hình mới (nếu muốn đổi)</label>
            <input type="file" class="form-control-file" name="picture" id="picture">
        </div>

        <button type="submit" class="btn btn-success">Cập nhật</button>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Hủy</a>
    </form>
</div>

<style>
    .edit-container {
        background-color: #fff8fb;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 15px rgba(0, 0, 0, 0.08);
        max-width: 600px;
        margin: 0 auto;
    }

    .edit-title {
        text-align: center;
        color: #d63384;
        font-size: 26px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .form-group {
        margin-bottom: 18px;
    }

    .form-control, .form-control-file {
        border-radius: 6px;
    }

    .preview-img {
        width: 150px;
        border-radius: 8px;
        border: 1px solid #ccc;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .btn {
        padding: 8px 20px;
        font-weight: 600;
        border-radius: 6px;
    }

    .btn-success {
        background-color: #28a745;
        border-color: #28a745;
    }

    .btn-secondary {
        margin-left: 10px;
    }
</style>
@endsection
