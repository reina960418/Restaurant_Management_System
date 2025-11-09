@extends('layouts.app')

@section('content')
<div class="container">
    <h1>產生對帳單</h1>
    <form action="{{ route('statements.generate') }}" method="POST" target="_blank">
        @csrf
        <div class="row">
            <div class="col-md-4 mb-3">
                <label for="supplier_id" class="form-label">廠商</label>
                <select class="form-control" id="supplier_id" name="supplier_id" required>
                    <option value="" disabled selected>請選擇一個廠商</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4 mb-3">
                <label for="start_date" class="form-label">開始日期</label>
                <input type="date" class="form-control" id="start_date" name="start_date" required>
            </div>
            <div class="col-md-4 mb-3">
                <label for="end_date" class="form-label">結束日期</label>
                <input type="date" class="form-control" id="end_date" name="end_date" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">產生對帳單</button>
    </form>
</div>
@endsection
