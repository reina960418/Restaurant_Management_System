@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">菜單管理</h1>
        <a href="{{ route('dishes.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新增菜色</a>
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
                        <th>價格</th>
                        <th>描述</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dishes as $dish)
                        <tr>
                            <td>{{ $dish->id }}</td>
                            <td><a href="{{ route('dishes.show', $dish->id) }}">{{ $dish->name }}</a></td>
                            <td>{{ number_format($dish->price, 2) }}</td>
                            <td>{{ Str::limit($dish->description, 50) }}</td>
                            <td>
                                <a href="{{ route('dishes.edit', $dish->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i> 編輯</a>
                                <form action="{{ route('dishes.destroy', $dish->id) }}" method="POST" style="display:inline;">
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
