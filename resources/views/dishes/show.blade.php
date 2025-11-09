@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">{{ $dish->name }}</h1>
        <a href="{{ route('dishes.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card">
        <div class="card-header">
            菜色詳情
        </div>
        <div class="card-body">
            <p><strong>價格:</strong> {{ number_format($dish->price, 2) }}</p>
            <p><strong>描述:</strong></p>
            <p>{{ $dish->description ?? '無' }}</p>
            
            <hr>

            <h5>所需食材</h5>
            @if($dish->ingredients->isEmpty())
                <p>尚未設定所需食材。</p>
            @else
                <ul class="list-group">
                    @foreach($dish->ingredients as $ingredient)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            {{ $ingredient->name }}
                            <span class="badge bg-primary rounded-pill">{{ $ingredient->pivot->quantity }} {{ $ingredient->unit }}</span>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
        <div class="card-footer">
            <a href="{{ route('dishes.edit', $dish->id) }}" class="btn btn-warning">編輯</a>
        </div>
    </div>
</div>
@endsection
