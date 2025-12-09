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
                    <label for="sales_data" class="form-label">選擇檔案 (CSV, TXT 或 PDF)</label>
                    <input class="form-control" type="file" id="sales_data" name="sales_data" required accept=".csv,.txt,.pdf">
                    <div class="form-text">
                        請上傳包含點餐紀錄的檔案。支援 CSV, TXT 或 PDF 格式。
                    </div>
                </div>
                <button id="submit-button" type="submit" class="btn btn-primary">
                    <span id="button-text">開始分析 (SSE)</span>
                    <span id="loading-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                </button>
            </form>
        </div>
    </div>

    <!-- Result Container -->
    <div id="result-card" class="card mt-4 d-none">
        <div class="card-header">
            分析報告 (即時串流)
        </div>
        <div class="card-body">
            <div id="analysis-result" class="markdown-content"></div>
        </div>
    </div>
</div>

<!-- Marked.js for Markdown rendering -->
<script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

<style>
.markdown-content {
    line-height: 1.6;
}
.markdown-content h1, .markdown-content h2, .markdown-content h3 {
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}
.markdown-content ul, .markdown-content ol {
    margin-left: 1.5rem;
}
.markdown-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 1rem 0;
}
.markdown-content th, .markdown-content td {
    border: 1px solid #dee2e6;
    padding: 0.5rem;
    text-align: left;
}
.markdown-content th {
    background-color: #f8f9fa;
}
.markdown-content code {
    background-color: #f1f3f5;
    padding: 0.2rem 0.4rem;
    border-radius: 3px;
}
.markdown-content pre {
    background-color: #f1f3f5;
    padding: 1rem;
    border-radius: 5px;
    overflow-x: auto;
}
.markdown-content blockquote {
    border-left: 4px solid #dee2e6;
    margin-left: 0;
    padding-left: 1rem;
    color: #6c757d;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('analysis-form');
    if (!form) {
        console.error('Analysis form not found!');
        return;
    }

    let rawMarkdown = '';

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        console.log('Form submitted via JS');
        
        const button = document.getElementById('submit-button');
        const buttonText = document.getElementById('button-text');
        const spinner = document.getElementById('loading-spinner');
        const resultCard = document.getElementById('result-card');
        const resultElement = document.getElementById('analysis-result');
        const formData = new FormData(this);

        button.disabled = true;
        buttonText.textContent = '分析中...';
        spinner.classList.remove('d-none');
        
        // Reset and show result container
        rawMarkdown = '';
        resultElement.innerHTML = '';
        resultCard.classList.remove('d-none');

        try {
            const response = await fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'text/event-stream'
                }
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const reader = response.body.getReader();
            const decoder = new TextDecoder();

            while (true) {
                const { value, done } = await reader.read();
                if (done) break;
                
                const text = decoder.decode(value, { stream: true });
                rawMarkdown += text;
                
                // Parse and render markdown in real-time
                resultElement.innerHTML = marked.parse(rawMarkdown);
            }

        } catch (error) {
            console.error('Fetch error:', error);
            rawMarkdown += '\n\n**[錯誤]** 分析過程中發生錯誤: ' + error.message;
            resultElement.innerHTML = marked.parse(rawMarkdown);
        } finally {
            button.disabled = false;
            buttonText.textContent = '開始分析 (SSE)';
            spinner.classList.add('d-none');
        }
    });
});
</script>
@endsection

