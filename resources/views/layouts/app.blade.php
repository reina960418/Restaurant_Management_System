<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
</head>
<body>
    <div id="app">
        <nav class="navbar navbar-expand-md navbar-dark bg-dark shadow-sm">
            <div class="container">
                <a class="navbar-brand" href="{{ url('/') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}"><i class="bi bi-house-door-fill"></i> 主頁</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('suppliers*') ? 'active' : '' }}" href="{{ route('suppliers.index') }}"><i class="bi bi-truck"></i> 廠商</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('ingredients*') ? 'active' : '' }}" href="{{ route('ingredients.index') }}"><i class="bi bi-egg-fried"></i> 食材</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('purchase_orders*') ? 'active' : '' }}" href="{{ route('purchase_orders.index') }}"><i class="bi bi-file-earmark-text-fill"></i> 採購單</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('goods_receipts*') ? 'active' : '' }}" href="{{ route('goods_receipts.index') }}"><i class="bi bi-box-seam-fill"></i> 進料單</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('dishes*') ? 'active' : '' }}" href="{{ route('dishes.index') }}"><i class="bi bi-journal-text"></i> 菜單管理</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->is('orders*') ? 'active' : '' }}" href="{{ route('orders.create') }}"><i class="bi bi-cart-fill"></i> 點餐</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-printer-fill"></i> 對帳單
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('statements.query') }}">進料對帳單</a></li>
                                <li><a class="dropdown-item" href="{{ route('order_statements.query') }}">點餐對帳單</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- JS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</body>
</html>

