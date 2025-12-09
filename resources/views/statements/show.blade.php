<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>對帳單 - {{ $supplier->name }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        @media print {
            .no-print {
                display: none;
            }
        }
        body {
            background-color: #fff;
        }
        .container {
            max-width: 960px;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="h3">對帳單</h1>
            <button class="btn btn-primary no-print" onclick="window.print()">列印</button>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-6"><strong>廠商:</strong> {{ $supplier->name }}</div>
                    <div class="col-6 text-end"><strong>對帳區間:</strong> {{ $startDate }} ~ {{ $endDate }}</div>
                </div>
            </div>
            <div class="card-body">
                @php $totalAmount = 0; @endphp
                    @if($goodsReceipts->isEmpty())
                        <p class="text-center">此區間內沒有進料紀錄。</p>
                    @else
                    @foreach($goodsReceipts as $receipt)
                        <h5 class="mt-4">進料單 #{{ $receipt->id }} - 日期: {{ $receipt->receipt_date }}</h5>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>食材</th>
                                    <th class="text-end">收到數量</th>
                                    <th class="text-end">單價</th>
                                    <th class="text-end">小計</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $receiptTotal = 0; @endphp
                                @foreach($receipt->items as $item)
                                    @php 
                                        $subtotal = $item->quantity_received * $item->price;
                                        $receiptTotal += $subtotal;
                                    @endphp
                                    <tr>
                                        <td>{{ $item->ingredient->name }}</td>
                                        <td class="text-end">{{ $item->quantity_received }}</td>
                                        <td class="text-end">{{ number_format($item->price, 2) }}</td>
                                        <td class="text-end">{{ number_format($subtotal, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="3" class="text-end"><strong>此單合計:</strong></td>
                                    <td class="text-end"><strong>{{ number_format($receiptTotal, 2) }}</strong></td>
                                </tr>
                            </tfoot>
                        </table>
                        @php $totalAmount += $receiptTotal; @endphp
                    @endforeach
                @endif
            </div>
            <div class="card-footer text-end">
                <h4 class="h5">總計金額: {{ number_format($totalAmount, 2) }}</h4>
            </div>
        </div>
    </div>
</body>
</html>
