@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">新增菜色</h1>
        <a href="{{ route('dishes.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="dish-form">
                <dish-form 
                    :ingredients="{{ json_encode($ingredients) }}" 
                    store-url="{{ route('dishes.store') }}"
                    index-url="{{ route('dishes.index') }}"
                ></dish-form>
            </div>
        </div>
    </div>
</div>
@endsection
