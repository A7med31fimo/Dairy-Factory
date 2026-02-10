@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<!-- Quick Action Buttons (mobile friendly, very large) -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <a href="{{ route('milk.create') }}" class="quick-action-btn">
            <i class="bi bi-droplet-fill" style="color: #3a7fd4;"></i>
            <span>جمع حليب</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('distribution.create') }}" class="quick-action-btn">
            <i class="bi bi-truck" style="color: #2565AE;"></i>
            <span>توزيع منتجات</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('expenses.create') }}" class="quick-action-btn">
            <i class="bi bi-wallet2" style="color: #e67e22;"></i>
            <span>إضافة مصروف</span>
        </a>
    </div>
    <div class="col-6 col-md-3">
        <a href="{{ route('reports.index') }}" class="quick-action-btn">
            <i class="bi bi-bar-chart-fill" style="color: #3498db;"></i>
            <span>عرض التقارير</span>
        </a>
    </div>
</div>

<!-- Today's Stats -->
<h5 class="fw-bold mb-3" style="color: #555;">
    <i class="bi bi-calendar-day me-2" style="color: #2565AE;"></i>
    إحصائيات اليوم
</h5>
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($todayMilk, 1) }}</div>
                    <div class="stat-label">لتر حليب جُمع</div>
                </div>
                <div class="stat-icon" style="background: #e8f0fa;">
                    <i class="fa-solid bi-droplet-fill" style="color: #3a7fd4;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($todayMilkValue, 0) }}</div>
                    <div class="stat-label">قيمة الحليب (جنيه)</div>
                </div>
                <div class="stat-icon" style="background: #e3f2fd;">
                    <i class="bi bi-coin" style="color: #1976d2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value">{{ number_format($todayDistribution, 0) }}</div>
                    <div class="stat-label">مبيعات اليوم (جنيه)</div>
                </div>
                <div class="stat-icon" style="background: #f3e5f5;">
                    <i class="bi bi-truck" style="color: #7b1fa2;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="stat-card">
            <div class="d-flex justify-content-between align-items-start">
                <div>
                    <div class="stat-value" style="color: #e53935;">{{ number_format($totalDebts, 0) }}</div>
                    <div class="stat-label">إجمالي الديون (جنيه)</div>
                </div>
                <div class="stat-icon" style="background: #ffebee;">
                    <i class="bi bi-file-earmark-text-fill" style="color: #e53935;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Records -->
<div class="row g-3">
    <!-- Recent Milk Collections -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-droplet-fill"></i> آخر عمليات جمع الحليب</span>
                <a href="{{ route('milk.index') }}" class="btn btn-sm btn-outline-success">
                    عرض الكل
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($recentMilk as $milk)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-bold">{{ $milk->farmer_name }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-car-front me-1"></i>{{ $milk->vehicle_number }} •
                            {{ \Carbon\Carbon::parse($milk->collection_date)->format('d/m') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="color: #2565AE;">{{ number_format($milk->quantity_liters, 1) }} ل</div>
                        <div class="text-muted small">{{ number_format($milk->total_amount, 0) }} جنيه</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">لا توجد سجلات</div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Distributions -->
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span><i class="bi bi-truck"></i> آخر عمليات التوزيع</span>
                <a href="{{ route('distribution.index') }}" class="btn btn-sm btn-outline-success">
                    عرض الكل
                </a>
            </div>
            <div class="card-body p-0">
                @forelse($recentDistributions as $dist)
                <div class="d-flex justify-content-between align-items-center px-3 py-2 border-bottom">
                    <div>
                        <div class="fw-bold">{{ $dist->shop_name }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-person-fill me-1"></i>{{ $dist->driver_name }} •
                            {{ \Carbon\Carbon::parse($dist->delivery_date)->format('d/m') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold" style="color: #7b1fa2;">{{ number_format($dist->total_value, 0) }} جنيه</div>
                        <div class="text-muted small">{{ $dist->items->count() }} منتجات</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-muted py-3">لا توجد سجلات</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- More quick links for desktop -->
<div class="row g-3 mt-1">
    <div class="col-md-4">
        <a href="{{ route('production.create') }}" class="quick-action-btn d-flex align-items-center gap-3" style="text-align: right; padding: 15px 20px;">
            <i class="bi bi-boxes fs-5" style="color: #e67e22;"></i>
            <span>تسجيل إنتاج جديد</span>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('debts.create') }}" class="quick-action-btn d-flex align-items-center gap-3" style="text-align: right; padding: 15px 20px;">
            <i class="bi bi-file-earmark-text-fill fs-5" style="color: #e53935;"></i>
            <span>تسجيل دين جديد</span>
        </a>
    </div>
    <div class="col-md-4">
        <a href="{{ route('expenses.create') }}" class="quick-action-btn d-flex align-items-center gap-3" style="text-align: right; padding: 15px 20px;">
            <i class="bi bi-receipt-cutoff fs-5" style="color: #f39c12;"></i>
            <span>تسجيل مصروف</span>
        </a>
    </div>
</div>
@endsection