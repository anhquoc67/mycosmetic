@extends ('layouts.admin')

@section ('title', 'Chi tiết đơn hàng')

@section('content')
<div class="container">
    <h3>🧾 Chi tiết đơn hàng</h3>

    <div class="mb-4">
        <p><strong>Mã hóa đơn:</strong> {{ $order->order_code }}</p>
        <p><strong>Khách hàng:</strong> {{ $order->user->name }}</p>
        <p><strong>Email:</strong> {{ $order->user->email }}</p>
        <p><strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
        <p><strong>Giao đến:</strong> {{ $ward ?? 'N/A'}}, {{ $district ?? 'N/A'}}, {{ $province ?? 'N/A'}}</p> 
        <p><strong>Trạng thái:</strong>
            <span class="badge
                @if($order->delivery_status === 'Đã đặt hàng') bg-warning
                @elseif($order->delivery_status === 'Đang giao hàng') bg-primary
                @elseif($order->delivery_status === 'Đã giao xong') bg-success
                @endif">
                {{ $order->delivery_status }}
            </span>
        </p>
    </div>

    <h5>📦 Danh sách sản phẩm:</h5>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Mã SP</th>
                <th>Ảnh</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach ($order->items as $item)
                @php
                    $subtotal = $item->price * $item->quantity;
                    $total += $subtotal;
                @endphp
                <tr>
                    <td>{{ $item->product->id }}</td>
                    <td><img src="{{ asset('image/products/' . $item->product->image) }}" width="60"></td>
                    <td>{{ $item->name }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }}đ</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($subtotal, 0, ',', '.') }}đ</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="text-end mt-3">
        <h5>💰 Tổng cộng: <strong>{{ number_format($total, 0, ',', '.') }}đ</strong></h5>
    </div>
    <hr class="my-4">

    <h5>🔄 Cập nhật trạng thái đơn hàng</h5>

    <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
        @csrf
        @method('PATCH')

        <div class="row align-items-center">
            <div class="col-auto">
                <label for="delivery_status" class="col-form-label">Trạng thái mới:</label>
            </div>
            <div class="col-auto">
                <select class="form-select" name="delivery_status" id="delivery_status">
                    @if($order->delivery_status === 'Đã đặt hàng')
                        <option value="Đang giao hàng">Đang giao hàng</option>
                    @elseif($order->delivery_status === 'Đang giao hàng')
                        <option value="Đã giao xong">Đã giao xong</option>
                    @elseif($order->delivery_status === 'Đã giao xong')
                        <option value="Đã giao xong" selected>Đã giao xong</option>
                    @endif
                </select>

            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-success">✔ Cập nhật</button>
            </div>

            <div class="text-end mt-3">
                <a href="{{ route('admin.orders.vieworders', ['user' => $order->user->id]) }}" class="btn btn-secondary">← Quay lại lịch sử</a>
            </div>
        </div>
    </form>

    @if (session('success'))
        <div class="alert alert-success mt-3">
            {{ session('success') }}
        </div>
    @endif

</div>
@endsection