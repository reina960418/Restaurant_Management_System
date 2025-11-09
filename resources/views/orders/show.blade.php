@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">訂單詳情 #{{ $order->id }}</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            訂單資訊
        </div>
        <div class="card-body">
            <p><strong>桌號:</strong> {{ $order->table_number ?? 'N/A' }}</p>
            <p><strong>總金額:</strong> NT$ {{ number_format($order->total_amount, 2) }}</p>
            <p><strong>狀態:</strong> 
                <span class="badge 
                    @if($order->status == 'pending') bg-secondary 
                    @elseif($order->status == 'completed') bg-success 
                    @elseif($order->status == 'cancelled') bg-danger 
                    @endif">
                    {{ $order->status }}
                </span>
            </p>
            <p><strong>訂單日期:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            訂單品項
        </div>
        <div class="card-body">
            @if($order->items->isEmpty())
                <p>此訂單沒有任何品項。</p>
            @else
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>菜色</th>
                            <th>數量</th>
                            <th>單價</th>
                            <th>小計</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->dish->name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price_at_order, 2) }}</td>
                                <td>{{ number_format($item->quantity * $item->price_at_order, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" class="text-end">總計:</th>
                            <th>NT$ {{ number_format($order->total_amount, 2) }}</th>
                        </tr>
                    </tfoot>
                </table>
            @endif
        </div>
    </div>
</div>
@endsection
