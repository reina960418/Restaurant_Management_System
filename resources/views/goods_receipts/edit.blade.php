@extends('layouts.app')

@section('content')
<div class="container">
    <h1>編輯進料單 #{{ $goodsReceipt->id }}</h1>
    <div id="goods-receipt-form">
        <goods-receipt-form 
            :purchase-orders="{{ json_encode($purchaseOrders) }}" 
            :initial-data="{{ json_encode($goodsReceipt) }}"
            :is-editing="true"
            update-url="{{ route('goods_receipts.update', $goodsReceipt->id) }}"
            index-url="{{ route('goods_receipts.index') }}"
        ></goods-receipt-form>
    </div>
</div>
@endsection
