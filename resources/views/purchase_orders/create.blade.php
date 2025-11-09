@extends('layouts.app')

@section('content')
<div class="container">
    <h1>新增採購單</h1>
    <div id="purchase-order-form">
        <purchase-order-form 
            :suppliers="{{ json_encode($suppliers) }}" 
            :ingredients="{{ json_encode($ingredients) }}"
            store-url="{{ route('purchase_orders.store') }}"
            index-url="{{ route('purchase_orders.index') }}"
        ></purchase-order-form>
    </div>
</div>
@endsection
