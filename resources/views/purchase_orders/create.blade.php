@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">新增採購單</h1>
        <a href="{{ route('purchase_orders.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="purchase-order-form">
                <purchase-order-form 
                    :suppliers="{{ json_encode($suppliers) }}" 
                    :ingredients="{{ json_encode($ingredients) }}"
                    store-url="{{ route('purchase_orders.store') }}"
                    index-url="{{ route('purchase_orders.index') }}"
                ></purchase-order-form>
            </div>
        </div>
    </div>
</div>
@endsection
