@extends('layouts.app')

@section('content')
<div class="container">
    <h1>編輯採購單 #{{ $purchaseOrder->id }}</h1>
    <div id="purchase-order-form">
        <purchase-order-form 
            :suppliers="{{ json_encode($suppliers) }}" 
            :ingredients="{{ json_encode($ingredients) }}"
            :initial-data="{{ json_encode($purchaseOrder) }}"
            :is-editing="true"
            update-url="{{ route('purchase_orders.update', $purchaseOrder->id) }}"
            index-url="{{ route('purchase_orders.index') }}"
        ></purchase-order-form>
    </div>
</div>
@endsection
