@extends('layouts.app')

@section('content')
    <div class="banner-container" style="width: 100%; max-width: 800px; margin: auto; position: relative; overflow: hidden;">
        <img src="{{ asset('image/sp39.png') }}" class="banner-slide" style="width: 100%; display: none;">
        <img src="{{ asset('image/sp35.png') }}" class="banner-slide" style="width: 100%; display: none;">
        <img src="{{ asset('image/sp36.png') }}" class="banner-slide" style="width: 100%; display: none;">
        <img src="{{ asset('image/sp37.png') }}" class="banner-slide" style="width: 100%; display: none;">
    </div>

    <div class="container mt-5">
        <h3 class="mb-4">Sản phẩm mới nhất</h3>
        <div class="row">
            @foreach ($products as $product)
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="card h-100 ">
                        <img src="{{ asset('image/products/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: contain;">
                        <div class="card-body d-flex flex-column justify-content-between">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text"><strong>Thương hiệu:</strong> {{ $product->brand }}</p>
                            <p class="card-text"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p>
                            <p class="card-text"><strong>Đã bán:</strong> {{ $product->sold }} lượt</p>
                            <p class="card-text"><small>Mã SP: {{ $product->id }}</small></p>
                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="id" value="{{ $product->id }}">
                                <input type="hidden" name="name" value="{{ $product->name }}">
                                <input type="hidden" name="price" value="{{ $product->price }}">
                                <input type="hidden" name="image" value="{{ $product->image }}">
                                <input type="hidden" name="quantity" value="1">
                                <button class="btn btn-primary btn-add-to-cart" data-id="{{ $product->id }}">
                                    🛒 Thêm vào giỏ
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
    
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        let currentSlide = 0;
        const slides = document.querySelectorAll('.banner-slide');
        const totalSlides = slides.length;

        function showSlide(index) {
            slides.forEach((slide, i) => {
                slide.style.display = i === index ? 'block' : 'none';});
        }

        function nextSlide() {
            currentSlide = (currentSlide + 1) % totalSlides;
            showSlide(currentSlide);
        }

        showSlide(currentSlide);
        setInterval(nextSlide, 3000);
    });
</script>
@endsection