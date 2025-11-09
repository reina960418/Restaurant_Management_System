@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">點餐</h1>
        <a href="{{ route('orders.index') }}" class="btn btn-secondary">查看所有訂單</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="order-form">
                <order-form 
                    :dishes="{{ json_encode($dishes) }}" 
                    store-url="{{ route('orders.store') }}"
                    index-url="{{ route('orders.index') }}"
                ></order-form>
            </div>
        </div>
    </div>
</div>
@endsection
