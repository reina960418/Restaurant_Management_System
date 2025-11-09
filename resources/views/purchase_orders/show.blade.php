@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>採購單詳細資料 #{{ $purchaseOrder->id }}</h1>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>廠商:</strong> {{ $purchaseOrder->supplier->name }}
            </div>
            <div class="mb-3">
                <strong>訂單日期:</strong> {{ $purchaseOrder->order_date }}
            </div>
            <div class="mb-3">
                <strong>預計到貨日:</strong> {{ $purchaseOrder->expected_date ?? 'N/A' }}
            </div>
            <div class="mb-3">
                <strong>狀態:</strong> {{ $purchaseOrder->status }}
            </div>
            <div class="mb-3">
                <strong>建立時間:</strong> {{ $purchaseOrder->created_at }}
            </div>
            <div class="mb-3">
                <strong>更新時間:</strong> {{ $purchaseOrder->updated_at }}
            </div>

            <h3 class="mt-4">採購品項</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>食材</th>
                        <th>數量</th>
                        <th>單價</th>
                        <th>小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseOrder->items as $item)
                        <tr>
                            <td>{{ $item->ingredient->name }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity * $item->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>總金額:</strong></td>
                        <td><strong>{{ $purchaseOrder->items->sum(fn($item) => $item->quantity * $item->price) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('purchase_orders.edit', $purchaseOrder->id) }}" class="btn btn-warning">編輯</a>
            <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">返回列表</a>
        </div>
    </div>
</div>
@endsection
