@extends('layouts.app')

@section('content')
@if(session('success'))
    <div class="alert alert-success text-center">
        {{ session('success') }}
    </div>
@endif

<div class="all-products">
    <h2>🧴 Tất Cả Sản Phẩm</h2>
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-3 col-sm-6 mb-4">
                <div class="card h-100 ">
                    <img src="{{ asset('image/products/' . $product->image) }}" class="card-img-top" style="height: 200px; object-fit: contain;">
                    <div class="card-body d-flex flex-column justify-content-between">
                        @if ($product->sold >= 5)
                            <span class="hot-sale-badge animate-badge">
                                <span class="badge-icon">🔥</span>
                                Bán chạy
                            </span>
                        @endif
                        @if ($product->discount_percent && $product->discount_percent > 0)
                            <span class="badge bg-danger position-absolute top-0 start-0 m-2" style="z-index:10;">
                                SALE -{{ $product->discount_percent }}%
                            </span>
                        @endif
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text"><strong>Thương hiệu:</strong> {{ $product->brand }}</p>
                        <!-- <p class="card-text"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p> -->
                         @if ($product->discount_percent && $product->discount_percent > 0)
                            <p class="card-text mb-1">
                                <span style="text-decoration: line-through; color: #888;">
                                    {{ number_format($product->price, 0, ',', '.') }}₫
                                </span>
                                <span style="font-size: 1.1em; color: #e74c3c; margin-left: 10px; font-weight:bold;">
                                    {{ number_format($product->price * (1 - $product->discount_percent/100), 0, ',', '.') }}₫
                                </span>
                            </p>
                            <p class="card-text text-danger mb-1"><strong>Giảm giá: {{ $product->discount_percent }}%</strong></p>
                        @else
                            <p class="card-text"><strong>Giá:</strong> {{ number_format($product->price, 0, ',', '.') }}₫</p>
                        @endif
                        <p class="card-text"><strong>Đã bán:</strong> {{ $product->sold }} lượt</p>
                        <p class="card-text"><small>Mã SP: {{ $product->id }}</small></p>
                        <form class="add-to-cart-form" data-id="{{ $product->id }}">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <button type="button" class="btn btn-sm btn-primary mt-2 add-to-cart-btn">🛒 Thêm vào giỏ</button>
                        </form>

                        <!-- <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <input type="hidden" name="id" value="{{ $product->id }}">
                            <input type="hidden" name="name" value="{{ $product->name }}">
                            <input type="hidden" name="price" value="{{ $product->price }}">
                            <input type="hidden" name="image" value="{{ $product->image }}">
                            <input type="hidden" name="quantity" value="1">
                            <button type="submit" class="btn btn-sm btn-primary mt-2">🛒 Thêm vào giỏ</button>
                        </form> -->
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if ($products->hasPages())
    <div class="text-center mt-4">
        <ul class="pagination pagination-sm justify-content-center">
        {{-- Previous Page Link --}}
        @if ($products->onFirstPage())
            <li class="disabled"><span>&laquo;</span></li>
        @else
            <li><a href="{{ $products->previousPageUrl() }}" rel="prev">&laquo;</a></li>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            @if ($page == $products->currentPage())
                <li class="active"><span>{{ $page }}</span></li>
            @else
                <li><a href="{{ $url }}">{{ $page }}</a></li>
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($products->hasMorePages())
            <li><a href="{{ $products->nextPageUrl() }}" rel="next">&raquo;</a></li>
        @else
            <li class="disabled"><span>&raquo;</span></li>
        @endif
        </ul>
    </div>
    @endif
</div>
@endsection

 @section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.add-to-cart-btn').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            var form = this.closest('.add-to-cart-form');
            var id = form.dataset.id;
            var quantity = form.querySelector('input[name="quantity"]').value;
            var token = form.querySelector('input[name="_token"]').value;

            fetch("/cart/add/" + id, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": token,
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    id: id,
                    quantity: quantity
                })
            })
            .then(response => response.json())
            .then(data => {
                if(data.success){
                    // Cập nhật số lượng giỏ trên header (nếu có)
                    if(document.getElementById('cart-count'))
                        document.getElementById('cart-count').textContent = data.cart_count;
                    // Hiện thông báo nhỏ ở góc, hoặc alert (tùy bạn)
                    showToast(data.message); // hoặc alert(data.message);
                }
            });
        });
    });
});

// Ví dụ show toast (nếu muốn popup đẹp hơn, tùy bạn cài thêm thư viện/toast)
function showToast(msg) {
    alert(msg);
}
</script>
@endsection

