@extends('layouts.app')
@section('title', 'التقرير')

@section('content')
<!-- Actions bar -->
<div class="page-header no-print">
    <h1><i class="bi bi-bar-chart-fill"></i> التقرير - {{ $period_label }}</h1>
    <div class="d-flex gap-2 flex-wrap">
        <!-- PDF Export -->
        <form method="POST" action="{{ route('reports.pdf') }}" class="d-inline">
            @csrf
            <input type="hidden" name="period" value="{{ $period }}">
            <input type="hidden" name="date_from" value="{{ $date_from->format('Y-m-d') }}">
            <input type="hidden" name="date_to" value="{{ $date_to->format('Y-m-d') }}">
            <button type="submit" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf-fill me-1"></i> تصدير PDF
            </button>
        </form>
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer-fill me-1"></i> طباعة
        </button>
        <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> رجوع
        </a>
    </div>
</div>

<!-- Report Header -->
<div class="card mb-3" style="border: 2px solid #2565AE;">
    <div class="card-body text-center py-4">
        <h2 class="fw-bold mb-1" style="color: #2565AE;">
            <i class="bi bi-building-fill-gear me-2"></i>مصنع الألبان
        </h2>
        <h4 class="text-muted mb-1">تقرير {{ $period_label }}</h4>
        <p class="text-muted mb-0 small">
            من {{ $date_from->format('d/m/Y') }} إلى {{ $date_to->format('d/m/Y') }}
        </p>
    </div>
</div>

<!-- Summary Cards -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background: #e8f0fa;">
                <i class="bi bi-droplet-fill" style="color: #3a7fd4;"></i>
            </div>
            <div class="stat-value" style="color: #3a7fd4;">{{ number_format($totalMilkLiters, 1) }}</div>
            <div class="stat-label">لتر حليب مجموع</div>
            <div class="small text-muted mt-1">{{ number_format($totalMilkValue, decimals: 0) }} جنيه</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background: #fff3e0;">
                <i class="bi bi-boxes" style="color: #e67e22;"></i>
            </div>
            <div class="stat-value" style="color: #e67e22;">{{ $productions->count() }}</div>
            <div class="stat-label">دفعة إنتاج</div>
            <div class="small text-muted mt-1">{{ $productions->sum('quantity') }} وحدة</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background: #f3e5f5;">
                <i class="bi bi-truck" style="color: #7b1fa2;"></i>
            </div>
            <div class="stat-value" style="color: #7b1fa2;">{{ number_format($totalDistributionValue, 0) }}</div>
            <div class="stat-label">جنيه مبيعات</div>
            <div class="small text-muted mt-1">{{ $totalDistributions }} عملية توزيع</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card text-center">
            <div class="stat-icon mx-auto mb-2" style="background: #ffebee;">
                <i class="bi bi-wallet2" style="color: #e53935;"></i>
            </div>
            <div class="stat-value" style="color: #e53935;">{{ number_format($totalExpenses, 0) }}</div>
            <div class="stat-label">جنيه مصروفات</div>
        </div>
    </div>
</div>

<!-- Net Balance -->
<div class='card mb-3' style="background: {{ $netBalance >= 0 ? '#e8f0fa' : '#ffebee' }}; border: 2px solid {{ $netBalance >= 0 ? '#3a7fd4' : '#e53935' }};">
    <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <div class="fw-bold fs-5">الرصيد الصافي للفترة</div>
            <div class="small text-muted">مبيعات + قيمة حليب - مصروفات</div>
        </div>
        <div class="fw-bold display-6" style="color: {{ $netBalance >= 0 ? '#2565AE' : '#e53935' }};">
            {{ $netBalance >= 0 ? '+' : '' }}{{ number_format($netBalance, 2) }} جنيه
        </div>
    </div>
</div>

<div class="row g-3">
    <!-- Milk Collection Details -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-droplet-fill"></i> تفاصيل جمع الحليب
                <span class="badge bg-success ms-2">{{ $milkCollections->count() }} عملية</span>
            </div>
            <div class="card-body p-0">
                @if($milkCollections->count() > 0)
                <div style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>المزارع</th>
                                <th>الكمية</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($milkCollections as $milk)
                            <tr>
                                <td>{{ $milk->farmer_name }}</td>
                                <td>{{ number_format($milk->quantity_liters, 1) }} ل</td>
                                <td class="fw-bold text-success">{{ number_format($milk->total_amount, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #e8f0fa;">
                                <td class="fw-bold">الإجمالي</td>
                                <td class="fw-bold">{{ number_format($totalMilkLiters, 1) }} ل</td>
                                <td class="fw-bold text-success">{{ number_format($totalMilkValue, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-3 mb-0">لا توجد بيانات</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Production Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-boxes"></i> ملخص الإنتاج
            </div>
            <div class="card-body p-0">
                @if($productionByType->count() > 0)
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>النوع</th>
                            <th>عدد الدفعات</th>
                            <th>الكمية</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $ptypes = \App\Models\Production::productTypes(); @endphp
                        @foreach($productionByType as $type => $items)
                        <tr>
                            <td>{{ $ptypes[$type] ?? $type }}</td>
                            <td>{{ $items->count() }}</td>
                            <td class="fw-bold">{{ number_format($items->sum('quantity'), 1) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                @else
                <p class="text-muted text-center py-3 mb-0">لا توجد بيانات</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Distribution Summary -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-truck"></i> ملخص التوزيع
                <span class="badge bg-purple ms-2" style="background: #7b1fa2 !important;">{{ $totalDistributions }} عملية</span>
            </div>
            <div class="card-body p-0">
                @if($distributions->count() > 0)
                <div style="max-height: 250px; overflow-y: auto;">
                    <table class="table table-sm mb-0">
                        <thead>
                            <tr>
                                <th>المحل</th>
                                <th>التاريخ</th>
                                <th>الإجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($distributions as $dist)
                            <tr>
                                <td>{{ $dist->shop_name }}</td>
                                <td class="small">{{ \Carbon\Carbon::parse($dist->delivery_date)->format('d/m') }}</td>
                                <td class="fw-bold" style="color: #7b1fa2;">{{ number_format($dist->total_value, 0) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr style="background: #f3e5f5;">
                                <td colspan="2" class="fw-bold">الإجمالي</td>
                                <td class="fw-bold" style="color: #7b1fa2;">{{ number_format($totalDistributionValue, 0) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                @else
                <p class="text-muted text-center py-3 mb-0">لا توجد بيانات</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Expenses by category -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-wallet2"></i> تفاصيل المصروفات
            </div>
            <div class="card-body p-0">
                @if($expensesByCategory->count() > 0)
                @php $cats = \App\Models\Expense::categories(); @endphp
                <table class="table table-sm mb-0">
                    <thead>
                        <tr>
                            <th>التصنيف</th>
                            <th>عدد</th>
                            <th>الإجمالي</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($expensesByCategory as $cat => $items)
                        <tr>
                            <td>{{ $cats[$cat] ?? $cat }}</td>
                            <td>{{ $items->count() }}</td>
                            <td class="fw-bold text-danger">{{ number_format($items->sum('amount'), 0) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #ffebee;">
                            <td colspan="2" class="fw-bold">الإجمالي</td>
                            <td class="fw-bold text-danger">{{ number_format($totalExpenses, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <p class="text-muted text-center py-3 mb-0">لا توجد مصروفات</p>
                @endif
            </div>
        </div>
    </div>

    <!-- Debts Summary -->
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <i class="bi bi-file-earmark-text-fill"></i> ملخص الديون
            </div>
            <div class="card-body">
                <div class="row g-3 text-center">
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3" style="background: #ffebee;">
                            <div class="text-muted small">إجمالي الديون المتبقية</div>
                            <div class="fw-bold fs-5 text-danger">{{ number_format($totalOutstandingDebts, 0) }} جنيه</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3" style="background: #fff8e1;">
                            <div class="text-muted small">ديون جديدة في الفترة</div>
                            <div class="fw-bold fs-5" style="color: #f39c12;">{{ number_format($newDebtValue, 0) }} جنيه</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3" style="background: #e8f0fa;">
                            <div class="text-muted small">دفعات مستلمة في الفترة</div>
                            <div class="fw-bold fs-5 text-success">{{ number_format($paymentsInPeriod, 0) }} جنيه</div>
                        </div>
                    </div>
                    <div class="col-6 col-md-3">
                        <div class="p-3 rounded-3" style="background: #f3e5f5;">
                            <div class="text-muted small">عدد ديون جديدة</div>
                            <div class="fw-bold fs-5" style="color: #7b1fa2;">{{ $newDebts->count() }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection