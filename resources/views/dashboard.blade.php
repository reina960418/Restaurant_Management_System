@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">主頁</h1>

    <!-- Quick Stats -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">待處理採購單</h5>
                    <p class="card-text fs-1">{{ $pendingPurchaseOrders }}</p>
                    <a href="{{ route('purchase_orders.index') }}" class="text-white">查看詳情 &rarr;</a>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card text-white bg-danger">
                <div class="card-body">
                    <h5 class="card-title">低庫存食材</h5>
                    <p class="card-text fs-1">{{ $lowStockIngredients }}</p>
                    <a href="{{ route('ingredients.index') }}" class="text-white">查看詳情 &rarr;</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    快速操作
                </div>
                <div class="card-body">
                    <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary">新增採購單</a>
                    <a href="{{ route('goods_receipts.create') }}" class="btn btn-success">新增進料單</a>
                    <a href="{{ route('statements.query') }}" class="btn btn-info">列印對帳單</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Goods Receipts -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    最近進料紀錄
                </div>
                <div class="card-body">
                    @if($recentGoodsReceipts->isEmpty())
                        <p>沒有最近的進料紀錄。</p>
                    @else
                        <ul class="list-group list-group-flush">
                            @foreach($recentGoodsReceipts as $receipt)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <a href="{{ route('goods_receipts.show', $receipt->id) }}">進料單 #{{ $receipt->id }}</a>
                                        <small class="d-block text-muted">廠商: {{ $receipt->supplier->name }}</small>
                                    </div>
                                    <span>{{ $receipt->receipt_date }}</span>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
