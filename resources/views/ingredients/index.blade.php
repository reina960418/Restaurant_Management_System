@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">食材列表</h1>
        <a href="{{ route('ingredients.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新增食材</a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card">
        <div class="card-body">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>名稱</th>
                        <th>單位</th>
                        <th>參考價格</th>
                        <th>庫存</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ingredients as $ingredient)
                        <tr>
                            <td>{{ $ingredient->id }}</td>
                            <td>{{ $ingredient->name }}</td>
                            <td>{{ $ingredient->unit }}</td>
                            <td>{{ number_format($ingredient->price, 2) }}</td>
                            <td>{{ $ingredient->stock }}</td>
                            <td>
                                <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i> 編輯</a>
                                <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('確定要刪除嗎？')"><i class="bi bi-trash-fill"></i> 刪除</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
