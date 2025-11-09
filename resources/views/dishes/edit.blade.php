@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">編輯菜色</h1>
        <a href="{{ route('dishes.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card">
        <div class="card-body">
            <div id="dish-form">
                <dish-form 
                    :ingredients="{{ json_encode($ingredients) }}" 
                    :initial-data="{{ json_encode($dish) }}"
                    :is-editing="true"
                    update-url="{{ route('dishes.update', $dish->id) }}"
                    index-url="{{ route('dishes.index') }}"
                ></dish-form>
            </div>
        </div>
    </div>
</div>
@endsection
