@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">新增進料單</h1>
        <a href="{{ route('goods_receipts.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="goods-receipt-form">
                <goods-receipt-form 
                    :purchase-orders="{{ json_encode($purchaseOrders) }}" 
                    store-url="{{ route('goods_receipts.store') }}"
                    index-url="{{ route('goods_receipts.index') }}"
                ></goods-receipt-form>
            </div>
        </div>
    </div>
</div>
@endsection
