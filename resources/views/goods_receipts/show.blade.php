@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>進料單詳細資料 #{{ $goodsReceipt->id }}</h1>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>採購單號:</strong> {{ $goodsReceipt->purchaseOrder->id }}
            </div>
            <div class="mb-3">
                <strong>廠商:</strong> {{ $goodsReceipt->supplier->name }}
            </div>
            <div class="mb-3">
                <strong>收貨日期:</strong> {{ $goodsReceipt->receipt_date }}
            </div>
            <div class="mb-3">
                <strong>備註:</strong> {{ $goodsReceipt->notes ?? 'N/A' }}
            </div>
            <div class="mb-3">
                <strong>建立時間:</strong> {{ $goodsReceipt->created_at }}
            </div>
            <div class="mb-3">
                <strong>更新時間:</strong> {{ $goodsReceipt->updated_at }}
            </div>

            <h3 class="mt-4">收貨品項</h3>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>食材</th>
                        <th>訂購數量</th>
                        <th>收到數量</th>
                        <th>退貨數量</th>
                        <th>退貨原因</th>
                        <th>單價</th>
                        <th>小計</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($goodsReceipt->items as $item)
                        <tr>
                            <td>{{ $item->ingredient->name }}</td>
                            <td>{{ $item->purchaseOrderItem->quantity ?? 'N/A' }}</td> {{-- Need to fetch original PO item quantity --}}
                            <td>{{ $item->quantity_received }}</td>
                            <td>{{ $item->quantity_returned }}</td>
                            <td>{{ $item->return_reason ?? 'N/A' }}</td>
                            <td>{{ $item->price }}</td>
                            <td>{{ $item->quantity_received * $item->price }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="6" class="text-end"><strong>總金額 (收到部分):</strong></td>
                        <td><strong>{{ $goodsReceipt->items->sum(fn($item) => $item->quantity_received * $item->price) }}</strong></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="card-footer">
            <a href="{{ route('goods_receipts.edit', $goodsReceipt->id) }}" class="btn btn-warning">編輯</a>
            <a href="{{ route('goods_receipts.index') }}" class="btn btn-secondary">返回列表</a>
        </div>
    </div>
</div>
@endsection
