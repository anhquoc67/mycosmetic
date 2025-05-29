@extends('layouts.admin')
@section('title', 'Edit Sale')

@section('content')
    <div class="container">
        <h3>Edit Sale Product</h3>
        <form action="{{ route('sales.update', $item->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- @method('POST') --}}

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
            <input type="text" name="name" class="form-control mb-2" value="{{ $item->name }}" required>

            <label>Brand:</label>
            <input type="text" name="brand" class="form-control mb-2" value="{{ $item->brand }}" required>

            <label>Price:</label>
            <input type="number" name="price" class="form-control mb-2" value="{{ $item->price }}" required>

            <label>Discount (%):</label>
            <input type="number" name="discount_percent" class="form-control mb-2" min="1" max="100"
                value="{{ $item->discount_percent }}" required>

            <label>Current Picture:</label><br>
            <img src="{{ asset('images/' . $item->picture) }}" width="150" class="mb-3"><br>

            <label>Change Picture (optional):</label>
            <input type="file" name="picture" class="form-control mb-2">

            <button type="submit" class="btn btn-primary">Update Sale</button>
            <a href="{{ url('/sales') }}" class="btn btn-secondary ms-2">Cancel</a>
        </form>
    </div>
@endsection
