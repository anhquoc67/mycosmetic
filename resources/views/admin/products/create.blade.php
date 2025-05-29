@extends('layouts.admin')

@section('title')
    Create Product
@endsection

@section('content')
    <div class="container">
        <form action="{{ url('/products/store') }}" method="post" enctype="multipart/form-data">
            <h3 class="title">Create New Product</h3>
            @csrf

            <!-- Hiển thị thông báo lỗi -->
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

            <div class="mt-3 mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <div class="mt-3 mb-3">
                <label for="brand" class="form-label">brand</label>
                <input type="text" name="brand" id="brand" class="form-control" required>
            </div>

            <div class="mt-3 mb-3">
                <label for="price" class="form-label">Price</label>
                <input type="number" name="price" id="price" class="form-control" min="0" max="5000000" required>
            </div>

            <div class="mt-3 mb-3">
                <label for="picture" class="form-label">Picture</label>
                <input type="file" name="picture" id="picture" class="form-control" required>
            </div>

            <div>
                <button type="submit" class="btn btn-success">Submit</button>
                <button type="reset" class="btn btn-secondary">Reset</button>
            </div>
        </form>
    </div>
@endsection
