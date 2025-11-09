<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>點餐對帳單</title>
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
            <h1 class="h3">點餐對帳單</h1>
            <button class="btn btn-primary no-print" onclick="window.print()">列印</button>
        </div>
        
        <div class="card">
            <div class="card-header">
                <div class="row">
                    <div class="col-12 text-center"><strong>對帳區間:</strong> {{ $startDate }} ~ {{ $endDate }}</div>
                </div>
            </div>
            <div class="card-body">
                @if($orders->isEmpty())
                    <p class="text-center">此區間內沒有任何訂單。</p>
                @else
                    @php $totalRevenue = 0; @endphp
                    @foreach($orders as $order)
                        <h5 class="mt-4">訂單 #{{ $order->id }} - 日期: {{ $order->created_at->format('Y-m-d H:i') }} (桌號: {{ $order->table_number ?? 'N/A' }})</h5>
                        <table class="table table-sm table-bordered">
                            <thead>
                                <tr>
                                    <th>菜色</th>
                                    <th class="text-end">數量</th>
                                    <th class="text-end">單價</th>
                                    <th class="text-end">小計</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>{{ $item->dish->name }}</td>
                                        <td class="text-end">{{ $item->quantity }}</td>
                                        <td class="text-end">{{ number_format($item->price_at_order, 2) }}</td>
                                        <td class="text-end">{{ number_format($item->quantity * $item->price_at_order, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        @php $totalRevenue += $order->total_amount; @endphp
                    @endforeach
                @endif
            </div>
            <div class="card-footer text-end">
                <h4 class="h5">總營業額: NT$ {{ number_format($totalRevenue, 2) }}</h4>
            </div>
        </div>
    </div>
</body>
</html>
