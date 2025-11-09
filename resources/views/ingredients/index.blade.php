@extends('layouts.app')

@section('content')
<div class="container">
    <h1>食材列表</h1>
    <a href="{{ route('ingredients.create') }}" class="btn btn-primary">新增食材</a>
    <table class="table mt-3">
        <thead>
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
                    <td>{{ $ingredient->price }}</td>
                    <td>{{ $ingredient->stock }}</td>
                    <td>
                        <a href="{{ route('ingredients.edit', $ingredient->id) }}" class="btn btn-warning">編輯</a>
                        <form action="{{ route('ingredients.destroy', $ingredient->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger">刪除</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
