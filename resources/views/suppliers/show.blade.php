@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header">
            <h1>廠商詳細資料</h1>
        </div>
        <div class="card-body">
            <div class="mb-3">
                <strong>ID:</strong> {{ $supplier->id }}
            </div>
            <div class="mb-3">
                <strong>名稱:</strong> {{ $supplier->name }}
            </div>
            <div class="mb-3">
                <strong>聯絡人:</strong> {{ $supplier->contact_person }}
            </div>
            <div class="mb-3">
                <strong>電話:</strong> {{ $supplier->phone }}
            </div>
            <div class="mb-3">
                <strong>Email:</strong> {{ $supplier->email }}
            </div>
            <div class="mb-3">
                <strong>地址:</strong> {{ $supplier->address }}
            </div>
            <div class="mb-3">
                <strong>建立時間:</strong> {{ $supplier->created_at }}
            </div>
            <div class="mb-3">
                <strong>更新時間:</strong> {{ $supplier->updated_at }}
            </div>
        </div>
        <div class="card-footer">
            <a href="{{ route('suppliers.edit', $supplier->id) }}" class="btn btn-warning">編輯</a>
            <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">返回列表</a>
        </div>
    </div>
</div>
@endsection
