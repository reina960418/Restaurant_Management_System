@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">訂單列表</h1>
        <a href="{{ route('orders.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg"></i> 新增訂單</a>
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
                        <th>桌號</th>
                        <th>總金額</th>
                        <th>狀態</th>
                        <th>訂單日期</th>
                        <th>操作</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr>
                            <td>{{ $order->id }}</td>
                            <td>{{ $order->table_number ?? 'N/A' }}</td>
                            <td>{{ number_format($order->total_amount, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'pending') bg-secondary 
                                    @elseif($order->status == 'completed') bg-success 
                                    @elseif($order->status == 'cancelled') bg-danger 
                                    @endif">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-info"><i class="bi bi-eye-fill"></i> 查看</a>
                                {{-- <a href="{{ route('orders.edit', $order->id) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil-fill"></i> 編輯</a> --}}
                                <form action="{{ route('orders.destroy', $order->id) }}" method="POST" style="display:inline;">
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
