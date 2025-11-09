@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">產生點餐對帳單</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">返回主頁</a>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('order_statements.generate') }}" method="POST" target="_blank">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="start_date" class="form-label">開始日期</label>
                        <input type="date" class="form-control" id="start_date" name="start_date" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="end_date" class="form-label">結束日期</label>
                        <input type="date" class="form-control" id="end_date" name="end_date" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">產生對帳單</button>
            </form>
        </div>
    </div>
</div>
@endsection
