@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="mb-0">AI 銷售分析結果</h1>
        <a href="{{ route('ai.upload.form') }}" class="btn btn-secondary">返回上傳</a>
    </div>

    <div class="card">
        <div class="card-header">
            分析報告
        </div>
        <div class="card-body">
            @if(isset($analysis))
                <pre style="white-space: pre-wrap; font-family: inherit;">{{ $analysis }}</pre>
            @else
                <p class="text-danger">無法獲取分析結果。</p>
            @endif
        </div>
    </div>
</div>
@endsection
