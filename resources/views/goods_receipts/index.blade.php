@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">進料單列表</h1>
        <a href="{{ route('goods_receipts.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新增進料單</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>採購單 ID</th>
                        <th>廠商</th>
                        <th>收貨日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goodsReceipts as $goodsReceipt)
                        <tr>
                            <td>{{ $goodsReceipt->id }}</td>
                            <td><a href="{{ route('purchase_orders.show', $goodsReceipt->purchase_order_id) }}">#{{ $goodsReceipt->purchase_order_id }}</a></td>
                            <td>{{ $goodsReceipt->supplier->name }}</td>
                            <td>{{ $goodsReceipt->receipt_date }}</td>
                            <td>
                                <a href="{{ route('goods_receipts.show', $goodsReceipt->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye-fill"></i> 查看</a>
                                <a href="{{ route('goods_receipts.edit', $goodsReceipt->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i> 編輯</a>
                                <form action="{{ route('goods_receipts.destroy', $goodsReceipt->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要刪除嗎？')"><i class="bi bi-trash-fill"></i> 刪除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
