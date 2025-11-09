@extends('layouts.app')

@section('content')
<div class="container">
    <h1>採購單列表</h1>
    <a href="{{ route('purchase_orders.create') }}" class="btn btn-primary">新增採購單</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>廠商</th>
                <th>訂單日期</th>
                <th>狀態</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchaseOrders as $po)
                <tr>
                    <td>{{ $po->id }}</td>
                    <td>{{ $po->supplier->name }}</td>
                    <td>{{ $po->order_date }}</td>
                    <td>{{ $po->status }}</td>
                    <td>
                        <a href="{{ route('purchase_orders.show', $po->id) }}" class="btn btn-info">查看</a>
                        <a href="{{ route('purchase_orders.edit', $po->id) }}" class="btn btn-warning">編輯</a>
                        <form action="{{ route('purchase_orders.destroy', $po->id) }}" method="POST" style="display:inline;">
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
