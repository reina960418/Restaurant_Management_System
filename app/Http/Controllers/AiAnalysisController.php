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
        set_time_limit(120); // Increase execution time to 120 seconds for this request

        $request->validate([
            'sales_data' => 'required|file|mimes:csv,txt',
        ]);

        $rawContent = $request->file('sales_data')->get();
        
        // Detect and convert encoding to UTF-8
        $encoding = mb_detect_encoding($rawContent, mb_detect_order(), true);
        if (!$encoding) {
            // If detection fails, fall back to a common encoding
            $encoding = 'UTF-8'; 
        }
        $fileContent = mb_convert_encoding($rawContent, 'UTF-8', $encoding);

        $prompt = "你是一位專業的餐廳數據分析師。請根據以下今日的點餐紀錄，提供一份簡潔的銷售分析報告。報告應包含：
1.  最受歡迎的菜色 Top 3。
2.  總銷售菜色數量。
3.  基於這些數據，提出一個具體的行銷建議。

點餐紀錄如下：
---
$fileContent
---
";

        try {
            $response = Http::timeout(120)->post(config('ollama.url') . '/api/generate', [
                'model' => config('ollama.model'),
                'prompt' => $prompt,
                'stream' => false,
            ]);

            if ($response->failed()) {
                return back()->with('error', '無法連接到 AI 分析服務。錯誤訊息：' . $response->body());
            }

            // Handle the newline-delimited JSON response from Ollama
            $lines = explode("\n", $response->body());
            $fullResponse = '';
            foreach ($lines as $line) {
                if (!empty($line)) {
                    $json = json_decode($line, true);
                    if (isset($json['response'])) {
                        $fullResponse .= $json['response'];
                    }
                }
            }
            $analysis = $fullResponse;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->with('error', '無法連接到 Ollama 服務，請確認服務正在運行中，且位址設定正確。錯誤訊息：' . $e->getMessage());
        } catch (\Exception $e) {
            return back()->with('error', '分析過程中發生錯誤：' . $e->getMessage());
        }

        return view('ai_analysis.result', compact('analysis'));
    }
}
