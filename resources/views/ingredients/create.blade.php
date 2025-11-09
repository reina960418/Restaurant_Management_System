@extends('layouts.app')

@section('content')
<div class="container">
    <h1>新增食材</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('ingredients.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="name" class="form-label">名稱</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="unit" class="form-label">單位</label>
            <input type="text" class="form-control" id="unit" name="unit" value="{{ old('unit') }}" required>
        </div>
        <div class="mb-3">
            <label for="price" class="form-label">參考價格</label>
            <input type="number" step="0.01" class="form-control" id="price" name="price" value="{{ old('price', 0) }}">
        </div>
        <div class="mb-3">
            <label for="stock" class="form-label">初始庫存</label>
            <input type="number" step="0.01" class="form-control" id="stock" name="stock" value="{{ old('stock', 0) }}" required>
        </div>
        <button type="submit" class="btn btn-primary">儲存</button>
        <a href="{{ route('ingredients.index') }}" class="btn btn-secondary">取消</a>
    </form>
</div>
@endsection
