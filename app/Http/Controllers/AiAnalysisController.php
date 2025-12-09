<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AiAnalysisController extends Controller
{
    public function showUploadForm()
    {
        return view('ai_analysis.upload');
    }

    public function analyze(Request $request)
    {
        try {
            set_time_limit(120); // Increase execution time to 120 seconds for this request

            $request->validate([
                'sales_data' => 'required|file|mimes:csv,txt,pdf',
            ]);

        $file = $request->file('sales_data');
        $extension = strtolower($file->getClientOriginalExtension());
        $rawContent = '';

        if ($extension === 'pdf') {
            try {
                // First try standard PDF text extraction
                $parser = new \Smalot\PdfParser\Parser();
                $pdf = $parser->parseFile($file->getPathname());
                $rawContent = $pdf->getText();
                
                // If text extraction returns empty, try OCR
                if (empty(trim($rawContent))) {
                    try {
                        $rawContent = $this->extractTextWithOcr($file->getPathname());
                    } catch (\Exception $ocrException) {
                        // Return error as stream for SSE
                        return response()->stream(function () use ($ocrException) {
                            echo "OCR 錯誤：" . $ocrException->getMessage() . "\n";
                            echo "請確認您已安裝 Tesseract OCR 和 Poppler，並已加入系統 PATH。";
                        }, 200, ['Content-Type' => 'text/event-stream']);
                    }
                }
            } catch (\Exception $e) {
                return response()->stream(function () use ($e) {
                    echo "PDF 解析錯誤：" . $e->getMessage();
                }, 200, ['Content-Type' => 'text/event-stream']);
            }
        } else {
            $rawContent = $file->get();
        }
        
        // Detect and convert encoding to UTF-8
        $encoding = mb_detect_encoding($rawContent, mb_detect_order(), true);
        if (!$encoding) {
            // If detection fails, fall back to a common encoding
            $encoding = 'UTF-8'; 
        }
        $fileContent = mb_convert_encoding($rawContent, 'UTF-8', $encoding);

        \Illuminate\Support\Facades\Log::info('Extracted File Content:', ['content' => substr($fileContent, 0, 500)]); // Log first 500 chars

        $prompt = "你是一位專業的餐廳數據分析師。請根據以下今日的點餐紀錄，提供一份簡潔的銷售分析報告。報告應包含：
1.  最受歡迎的菜色 Top 3。
2.  總銷售菜色數量。
3.  基於這些數據，提出一個具體的行銷建議。

點餐紀錄如下：
---
$fileContent
---
";

        return response()->stream(function () use ($prompt) {
            try {
                $response = Http::timeout(120)->post(config('ollama.url') . '/api/generate', [
                    'model' => config('ollama.model'),
                    'prompt' => $prompt,
                    'stream' => true, // Enable streaming from Ollama
                ]);

                if ($response->failed()) {
                    echo "Error: " . $response->body();
                    return;
                }

                $body = $response->toPsrResponse()->getBody();

                while (!$body->eof()) {
                    $line = \App\Http\Controllers\AiAnalysisController::readJsonLine($body);
                    if ($line) {
                        $json = json_decode($line, true);
                        if (isset($json['response'])) {
                            echo $json['response'];
                            if (ob_get_level() > 0) {
                                ob_flush();
                            }
                            flush();
                        }
                        if (isset($json['done']) && $json['done']) {
                            break;
                        }
                    }
                }
            } catch (\Exception $e) {
                echo "Error: " . $e->getMessage();
            }
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no', // Disable buffering for Nginx
        ]);
        } catch (\Exception $globalException) {
            return response()->stream(function () use ($globalException) {
                echo "**錯誤：** " . $globalException->getMessage() . "\n\n";
                echo "如果您嘗試上傳圖片式 PDF，請確認您已安裝 Tesseract OCR 和 Poppler。";
            }, 200, ['Content-Type' => 'text/event-stream']);
        }
    }

    private static function readJsonLine($stream) {
        $buffer = '';
        while (!$stream->eof()) {
            $char = $stream->read(1);
            if ($char === "\n") {
                return $buffer;
            }
            $buffer .= $char;
        }
        return $buffer;
    }

    /**
     * Extract text from PDF using OCR (for image-based PDFs)
     */
    private function extractTextWithOcr(string $pdfPath): string
    {
        $tempDir = storage_path('app/temp_ocr_' . uniqid());
        if (!is_dir($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        try {
            // Convert PDF to images using Spatie PDF-to-image
            $pdf = new \Spatie\PdfToImage\Pdf($pdfPath);
            $numberOfPages = $pdf->getNumberOfPages();
            
            $extractedText = '';
            
            for ($page = 1; $page <= $numberOfPages; $page++) {
                $imagePath = $tempDir . '/page_' . $page . '.png';
                $pdf->setPage($page)->saveImage($imagePath);
                
                // Run OCR on each page using Tesseract
                $ocr = new \thiagoalessio\TesseractOCR\TesseractOCR($imagePath);
                $ocr->lang('chi_tra', 'eng'); // Traditional Chinese + English
                
                $pageText = $ocr->run();
                $extractedText .= $pageText . "\n\n";
                
                // Clean up the temporary image
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
            
            return $extractedText;
            
        } finally {
            // Clean up temp directory
            if (is_dir($tempDir)) {
                rmdir($tempDir);
            }
        }
    }
}
