@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">新增廠商</h1>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">返回列表</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('suppliers.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">名稱</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                </div>
                <div class="mb-3">
                    <label for="contact_person" class="form-label">聯絡人</label>
                    <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person') }}">
                </div>
                <div class="mb-3">
                    <label for="phone" class="form-label">電話</label>
                    <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">地址</label>
                    <input type="text" class="form-control" id="address" name="address" value="{{ old('address') }}">
                </div>
                <button type="submit" class="btn btn-primary">儲存</button>
            </form>
        </div>
    </div>
</div>
@endsection
