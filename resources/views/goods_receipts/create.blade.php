@extends('layouts.app')

@section('content')
<div class="container">
    <h1>新增進料單</h1>
    <div id="goods-receipt-form">
        <goods-receipt-form 
            :purchase-orders="{{ json_encode($purchaseOrders) }}" 
            store-url="{{ route('goods_receipts.store') }}"
            index-url="{{ route('goods_receipts.index') }}"
        ></goods-receipt-form>
    </div>
</div>
@endsection
