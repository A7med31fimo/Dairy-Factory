<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'نظام إدارة مصنع الألبان')</title>

    <!-- Bootstrap 5 RTL -->
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet"> -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Tajawal:wght@300;400;500;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #2565AE;
            --primary-light: #3a7fd4;
            --secondary: #f0f5fb;
            --accent: #e8f0fa;
            --warning-soft: #fff8e1;
            --danger-soft: #ffebee;
        }

        * {

            font-family: "Tajawal", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;

        }

        body {
            background: #f4f7f5;
            min-height: 100vh;
            font-size: 15px;
        }

        /* Navbar */
        .navbar {
            background: var(--primary) !important;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
            padding: 10px 0;
        }

        .navbar-brand {
            color: white !important;
            font-weight: 800;
            font-size: 1.2rem;
        }

        .navbar-brand i {
            margin-left: 8px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            font-weight: 600;
        }

        .nav-link:hover,
        .nav-link.active {
            color: white !important;
        }

        /* Sidebar */
        .sidebar {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.08);
            padding: 10px 0;
            position: sticky;
            top: 20px;
        }

        .sidebar .nav-link {
            color: #444 !important;
            font-size: 1rem;
            font-weight: 600;
            padding: 12px 20px;
            border-radius: 8px;
            margin: 2px 8px;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all 0.2s;
        }

        .sidebar .nav-link i {
            font-size: 1.1rem;
            width: 20px;
            text-align: center;
        }

        .sidebar .nav-link:hover {
            background: var(--accent);
            color: var(--primary) !important;
        }

        .sidebar .nav-link.active {
            background: var(--primary) !important;
            color: white !important;
        }

        .sidebar-title {
            color: #999;
            font-size: 0.75rem;
            font-weight: 700;
            padding: 8px 20px 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        /* Cards */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.07);
        }

        .card-header {
            background: white;
            border-bottom: 2px solid var(--accent);
            font-weight: 700;
            font-size: 1.05rem;
            padding: 15px 20px;
            border-radius: 12px 12px 0 0 !important;
        }

        .card-header i {
            color: var(--primary);
            margin-left: 8px;
        }

        /* Stat Cards */
        .stat-card {
            background: white;
            border-radius: 14px;
            padding: 20px;
            box-shadow: 0 2px 15px rgba(0, 0, 0, 0.07);
            transition: transform 0.2s;
        }

        .stat-card:hover {
            transform: translateY(-3px);
        }

        .stat-card .stat-icon {
            width: 55px;
            height: 55px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
        }

        .stat-card .stat-value {
            font-size: 1.6rem;
            font-weight: 800;
            color: #1a1a1a;
        }

        .stat-card .stat-label {
            color: #777;
            font-size: 0.85rem;
            font-weight: 600;
        }

        /* Buttons */
        .btn {
            font-weight: 700;
            border-radius: 10px;
            padding: 10px 20px;
            font-size: 0.95rem;
        }

        .btn-lg {
            padding: 14px 28px;
            font-size: 1.1rem;
        }

        .btn-primary {
            background: var(--primary);
            border-color: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-light);
            border-color: var(--primary-light);
        }

        .btn-success {
            background: #28a745;
            border-color: #28a745;
        }

        .btn-sm {
            padding: 6px 14px;
            font-size: 0.85rem;
        }

        /* Forms */
        .form-label {
            font-weight: 700;
            color: #333;
            margin-bottom: 5px;
        }

        .form-control,
        .form-select {
            border: 2px solid #e8ecef;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 0.95rem;
            transition: border-color 0.2s;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(26, 107, 60, 0.1);
        }

        .form-control.is-invalid {
            border-color: #dc3545;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            font-weight: 600;
        }

        .input-group-text {
            background: var(--accent);
            border: 2px solid #e8ecef;
            font-weight: 700;
            border-radius: 10px;
        }

        /* Tables */
        .table {
            font-size: 0.92rem;
        }

        .table th {
            font-weight: 700;
            background: var(--accent);
            color: #333;
            border: none;
        }

        .table td {
            vertical-align: middle;
        }

        .table-hover tbody tr:hover {
            background: #f0f5fb;
        }

        /* Alerts */
        .alert {
            border: none;
            border-radius: 10px;
            font-weight: 600;
        }

        /* Badges */
        .badge {
            font-size: 0.8rem;
            padding: 5px 10px;
            border-radius: 20px;
        }

        /* Bottom mobile nav */
        .mobile-bottom-nav {
            display: none;
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: white;
            box-shadow: 0 -3px 15px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 8px 0;
        }

        .mobile-bottom-nav a {
            flex: 1;
            text-align: center;
            color: #888;
            text-decoration: none;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 5px 2px;
        }

        .mobile-bottom-nav a i {
            display: block;
            font-size: 1.3rem;
            margin-bottom: 2px;
        }

        .mobile-bottom-nav a.active {
            color: var(--primary);
        }

        /* Main content padding for mobile bottom nav */
        @media (max-width: 767.98px) {
            .mobile-bottom-nav {
                display: flex !important;
            }

            .main-content {
                padding-bottom: 80px !important;
            }

            .sidebar {
                display: none !important;
            }

            .stat-card .stat-value {
                font-size: 1.3rem;
            }

            .btn-lg {
                padding: 12px 20px;
                font-size: 1rem;
            }
        }

        /* Quick action buttons */
        .quick-action-btn {
            background: white;
            border: 2px solid var(--accent);
            border-radius: 14px;
            padding: 20px 15px;
            text-align: center;
            color: #333;
            text-decoration: none;
            display: block;
            transition: all 0.2s;
            height: 100%;
        }

        .quick-action-btn:hover {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .quick-action-btn i {
            font-size: 2rem;
            display: block;
            margin-bottom: 10px;
        }

        .quick-action-btn span {
            font-weight: 700;
            font-size: 0.9rem;
        }

        /* Page header */
        .page-header {
            background: white;
            border-radius: 12px;
            padding: 20px 25px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
        }

        .page-header h1 {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1a1a1a;
            margin: 0;
        }

        .page-header h1 i {
            color: var(--primary);
            margin-left: 10px;
        }

        /* Item rows in distribution */
        .item-row {
            background: #f0f5fb;
            border-radius: 10px;
            padding: 15px;
            margin-bottom: 10px;
            border: 1px solid #e0f0e6;
        }

        /* Print */
        @media print {

            .sidebar,
            .navbar,
            .no-print,
            .mobile-bottom-nav {
                display: none !important;
            }

            .col-md-9 {
                width: 100% !important;
                flex: none !important;
                max-width: 100% !important;
            }
        }
    </style>
    @yield('styles')
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid px-3">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-building-fill-gear"></i>
                مصنع الألبان
            </a>
            <div class="d-flex align-items-center gap-2">
                <span class="text-white opacity-75 d-none d-md-inline">
                    <i class="bi bi-person-circle me-1"></i>
                    {{ auth()->user()->name ?? '' }}
                </span>
                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-sm" style="background:rgba(255,255,255,0.2); color:white; border:none;">
                        <i class="bi bi-box-arrow-right"></i>
                        <span class="d-none d-md-inline">خروج</span>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    <div class="container-fluid mt-3 px-3">
        <div class="row">
            <!-- Sidebar (desktop) -->
            <div class="col-md-3 col-lg-2 d-none d-md-block">
                <div class="sidebar">
                    <a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house-fill"></i> الرئيسية
                    </a>
                    <div class="sidebar-title">العمليات</div>
                    <a href="{{ route('milk.index') }}" class="nav-link {{ request()->routeIs('milk.*') ? 'active' : '' }}">
                        <i class="bi bi-droplet-fill"></i> جمع الحليب
                    </a>
                    <a href="{{ route('production.index') }}" class="nav-link {{ request()->routeIs('production.*') ? 'active' : '' }}">
                        <i class="bi bi-boxes"></i> الإنتاج
                    </a>
                    <a href="{{ route('distribution.index') }}" class="nav-link {{ request()->routeIs('distribution.*') ? 'active' : '' }}">
                        <i class="bi bi-truck"></i> التوزيع
                    </a>
                    <div class="sidebar-title">المالية</div>
                    <a href="{{ route('debts.index') }}" class="nav-link {{ request()->routeIs('debts.*') ? 'active' : '' }}">
                        <i class="bi bi-file-earmark-text-fill"></i> الديون
                    </a>
                    <a href="{{ route('expenses.index') }}" class="nav-link {{ request()->routeIs('expenses.*') ? 'active' : '' }}">
                        <i class="bi bi-wallet2"></i> المصروفات
                    </a>
                    <div class="sidebar-title">التقارير</div>
                    <a href="{{ route('reports.index') }}" class="nav-link {{ request()->routeIs('reports.*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart-fill"></i> التقارير
                    </a>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-md-9 col-lg-10 main-content">
                <!-- Flash Messages -->
                @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif
                @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <i class="bi bi-exclamation-circle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
                @endif

                @yield('content')
            </div>
        </div>
    </div>

    <!-- Mobile Bottom Navigation -->
    <div class="mobile-bottom-nav">
        <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <i class="bi bi-house-fill"></i>الرئيسية
        </a>
        <a href="{{ route('milk.index') }}" class="{{ request()->routeIs('milk.*') ? 'active' : '' }}">
            <i class="bi bi-droplet-fill"></i>الحليب
        </a>
        <a href="{{ route('distribution.index') }}" class="{{ request()->routeIs('distribution.*') ? 'active' : '' }}">
            <i class="bi bi-truck"></i>التوزيع
        </a>
        <a href="{{ route('debts.index') }}" class="{{ request()->routeIs('debts.*') ? 'active' : '' }}">
            <i class="bi bi-file-earmark-text-fill"></i>الديون
        </a>
        <a href="{{ route('reports.index') }}" class="{{ request()->routeIs('reports.*') ? 'active' : '' }}">
            <i class="bi bi-bar-chart-fill"></i>التقارير
        </a>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('scripts')
</body>

</html>