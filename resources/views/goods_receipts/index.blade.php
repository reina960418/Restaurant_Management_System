@extends('layouts.app')

@section('content')
<div class="container">
    <h1>進料/收料紀錄</h1>
    <a href="{{ route('goods_receipts.create') }}" class="btn btn-primary">新增進料單</a>
    <table class="table mt-3">
        <thead>
            <tr>
                <th>ID</th>
                <th>採購單號</th>
                <th>廠商</th>
                <th>收貨日期</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($goodsReceipts as $gr)
                <tr>
                    <td>{{ $gr->id }}</td>
                    <td>{{ $gr->purchaseOrder->id }}</td>
                    <td>{{ $gr->supplier->name }}</td>
                    <td>{{ $gr->receipt_date }}</td>
                    <td>
                        <a href="{{ route('goods_receipts.show', $gr->id) }}" class="btn btn-info">查看</a>
                        <a href="{{ route('goods_receipts.edit', $gr->id) }}" class="btn btn-warning">編輯</a>
                        <form action="{{ route('goods_receipts.destroy', $gr->id) }}" method="POST" style="display:inline;">
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
