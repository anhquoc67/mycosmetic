@extends('layouts.admin')
@section('title', 'Create Sale')

@section('content')
    <div class="container">
        <h3>Create Sale Product</h3>
        <form action="{{ route('sales.store') }}" method="POST" enctype="multipart/form-data">
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
            <label>Name:</label>
            <input type="text" name="name" class="form-control mb-2" required>

            <label>Brand:</label>
            <input type="text" name="brand" class="form-control mb-2" required>

            <label>Price:</label>
            <input type="number" name="price" class="form-control mb-2" required>

            <label>Discount (%):</label>
            <input type="number" name="discount_percent" class="form-control mb-2" min="1" max="100" required>

            <label>Picture:</label>
            <input type="file" name="picture" class="form-control mb-2" required>

            <button type="submit" class="btn btn-success">Create Sale</button>
        </form>
    </div>
@endsection
