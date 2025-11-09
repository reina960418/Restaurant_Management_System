@extends('layouts.app')

@section('content')
<div class="container">
    <h1>編輯廠商</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('suppliers.update', $supplier->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-3">
            <label for="name" class="form-label">名稱</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $supplier->name) }}" required>
        </div>
        <div class="mb-3">
            <label for="contact_person" class="form-label">聯絡人</label>
            <input type="text" class="form-control" id="contact_person" name="contact_person" value="{{ old('contact_person', $supplier->contact_person) }}">
        </div>
        <div class="mb-3">
            <label for="phone" class="form-label">電話</label>
            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone', $supplier->phone) }}">
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $supplier->email) }}">
        </div>
        <div class="mb-3">
            <label for="address" class="form-label">地址</label>
            <textarea class="form-control" id="address" name="address" rows="3">{{ old('address', $supplier->address) }}</textarea>
        </div>
        <button type="submit" class="btn btn-primary">更新</button>
        <a href="{{ route('suppliers.index') }}" class="btn btn-secondary">取消</a>
    </form>
</div>
@endsection
