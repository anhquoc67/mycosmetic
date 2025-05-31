@extends('layouts.admin')
@section('title')
    products
@endsection

@section('content')
    <h2 class="title">Danh Sách Sản Phẩm và Chức năng</h2>
    <hr>
    <div class="mt-3 mb-3">
        <a href="{{ route ('admin.products.create') }}" class="btn btn-primary btn-lg create-btn">
            Create New Product
        </a>
    </div>
    <div class="mt-3 mb-3">
        <form action="">
            <input type="text" name="sname" id="sname" placeholder="enter product name" value="{{ request('sname') }}">
            <button class="submit btn btn-dark">Search</button>
        </form>
    </div>
    <table class="table table-hover table-striped">
        <thead>
            <tr>
                <th>ID</th>
                <th>Product Name</th>
                <th>Brand</th>
                <th>Price</th>
                <th>Giá Sale</th>
                <th>Lượt mua</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($list as $item)
                <tr>
                    <td>{{ $item->id }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->brand }}</td>
                    <td>{{ number_format($item->price, 0, ',', '.') }} đ</td>
                    <td>
                        @if($item->discount_percent && $item->discount_percent > 0)
                            {{ number_format($item->price * (1 - $item->discount_percent/100), 0, ',', '.') }} đ
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ $item->sold }}</td>
                    <td>
                        @if ($item->deleted_at)
                            <span class="badge bg-secondary">Đã ẩn</span>
                            <a href="{{ route('admin.products.restore', $item->id) }}" class="btn btn-sm btn-success">Phục hồi</a>
                        @else
                            <a href="{{ route('admin.products.detail', $item->id) }}" class="btn btn-info">Detail</a>
                            <a href="{{ route('admin.products.edit', $item->id) }}" class="btn btn-warning">Edit</a>
                            <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                                Delete
                            </button>
                        @endif
                    </td>
                    

                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-center mt-4">
        {{ $list->links('pagination::bootstrap-5') }}
    </div>
    @foreach ($list as $item)
        @if (!$item->deleted_at)
            <div class="modal fade" id="deleteModal{{ $item->id }}" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" action="{{ route('admin.products.delete', $item->id) }}">
                        @csrf
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="deleteModalLabel">Xác nhận xóa</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Đóng"></button>
                            </div>
                            <div class="modal-body">
                                Bạn có chắc chắn muốn xóa sản phẩm <strong>{{ $item->name }}</strong>?
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                                <button type="submit" class="btn btn-danger">Xác nhận xóa</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    @endforeach
    <style>
        h2 {
            text-align: center;
        }

        .create-btn {
            width: 250px;
            font-weight: bold;
            border-radius: 8px;
        }

        table.table {
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
        }

        table.table thead {
            background-color: #343a40;
            color: white;
            text-transform: uppercase;
            font-size: 14px;
        }

        table.table tbody tr:hover {
            background-color: #f2f2f2;
        }

        table.table td,
        table.table th {
            vertical-align: middle;
            padding: 12px;
        }

        .btn {
            margin-right: 5px;
        }

        .title {
            font-size: 24px;
            font-weight: 600;
            margin-bottom: 20px;
            color: #343a40;
        }

        input#sname {
            padding: 8px;
            width: 300px;
            border-radius: 6px;
            border: 1px solid #ced4da;
            margin-right: 10px;
        }

        button.submit {
            padding: 8px 20px;
            font-weight: bold;
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
