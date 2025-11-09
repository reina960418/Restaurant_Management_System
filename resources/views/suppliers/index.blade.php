@extends('layouts.app')

@section('content')
<div class="container">
    <h1>廠商列表</h1>
    <a href="{{ route('suppliers.create') }}" class="btn btn-primary">新增廠商</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>名稱</th>
                <th>聯絡人</th>
                <th>電話</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($suppliers as $supplier)
                <tr>
                    <td>{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->contact_person }}</td>
                    <td>{{ $supplier->phone }}</td>
                    <td>
                        <a href="{{ route('suppliers.show', $supplier->id) }}" class="btn btn-info">查看</a>
                        <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">編輯</a>
                        <form action="{{ route('suppliers.destroy', $supplier->id) }}" method="POST" style="display:inline;">
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
