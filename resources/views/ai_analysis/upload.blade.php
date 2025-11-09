@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">AI 銷售分析</h1>
        <a href="{{ route('dashboard') }}" class="btn btn-secondary">返回主頁</a>
    </div>

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card">
        <div class="card-header">
            上傳點餐紀錄檔案
        </div>
        <div class="card-body">
            <form id="analysis-form" action="{{ route('ai.analyze') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="sales_data" class="form-label">選擇檔案 (CSV 或 TXT)</label>
                    <input class="form-control" type="file" id="sales_data" name="sales_data" required accept=".csv,.txt">
                    <div class="form-text">
                        請上傳包含點餐紀錄的文字檔。例如，每行一筆菜色，格式為：`菜色名稱,數量`。
                    </div>
                </div>
                <button id="submit-button" type="submit" class="btn btn-primary">
                    <span id="button-text">開始分析</span>
                    <span id="loading-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('analysis-form').addEventListener('submit', function() {
    const button = document.getElementById('submit-button');
    const buttonText = document.getElementById('button-text');
    const spinner = document.getElementById('loading-spinner');

    button.disabled = true;
    buttonText.textContent = '分析中...';
    spinner.classList.remove('d-none');
});
</script>
@endsection
