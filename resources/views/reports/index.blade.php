@extends('layouts.app')
@section('title', 'التقارير')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-bar-chart-fill"></i> التقارير والإحصائيات</h1>
</div>

<div class="card">
    <div class="card-header"><i class="bi bi-funnel-fill"></i> اختر الفترة الزمنية</div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('reports.generate') }}" id="reportForm">
            @csrf
            <!-- Quick period buttons -->
            <div class="row g-3 mb-4">
                <div class="col-6 col-md-3">
                    <button type="button" class="btn w-100 period-btn btn-outline-primary"
                            data-period="today" onclick="selectPeriod('today')">
                        <i class="bi bi-calendar-day d-block fs-4 mb-1"></i>
                        اليوم
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="btn w-100 period-btn btn-outline-primary"
                            data-period="week" onclick="selectPeriod('week')">
                        <i class="bi bi-calendar-week d-block fs-4 mb-1"></i>
                        آخر 7 أيام
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="btn w-100 period-btn btn-outline-primary"
                            data-period="month" onclick="selectPeriod('month')">
                        <i class="bi bi-calendar d-block fs-4 mb-1"></i>
                        آخر 30 يوم
                    </button>
                </div>
                <div class="col-6 col-md-3">
                    <button type="button" class="btn w-100 period-btn btn-outline-secondary"
                            data-period="custom" onclick="selectPeriod('custom')">
                        <i class="bi bi-calendar-days d-block fs-4 mb-1"></i>
                        تاريخ محدد
                    </button>
                </div>
            </div>

            <input type="hidden" name="period" id="period_input" value="">

            <!-- Custom date range -->
            <div id="custom_dates" class="row g-3 mb-4" style="display: none !important;">
                <div class="col-md-6">
                    <label class="form-label">من تاريخ</label>
                    <input type="date" name="date_from" class="form-control"
                           value="{{ date('Y-m-01') }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">إلى تاريخ</label>
                    <input type="date" name="date_to" class="form-control"
                           value="{{ date('Y-m-d') }}">
                </div>
            </div>

            @error('period')
            <div class="alert alert-danger py-2 mb-3">{{ $message }}</div>
            @enderror

            <button type="submit" class="btn btn-primary btn-lg w-100" id="generateBtn" disabled>
                <i class="bi bi-search me-2"></i>
                عرض التقرير
            </button>
        </form>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-3 mt-2">
    <div class="col-12">
        <div class="p-3 rounded-3 text-center" style="background: white; border: 2px dashed #ddd;">
            <i class="bi bi-graph-up fs-4 mb-2 opacity-50" style="color: #2565AE;"></i>
            <p class="text-muted mb-0">اختر فترة زمنية لعرض التقرير التفصيلي</p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function selectPeriod(period) {
    document.querySelectorAll('.period-btn').forEach(btn => {
        btn.classList.remove('btn-primary');
        btn.classList.add(btn.dataset.period === 'custom' ? 'btn-outline-secondary' : 'btn-outline-primary');
    });
    const btn = document.querySelector(`[data-period="${period}"]`);
    btn.classList.remove('btn-outline-primary', 'btn-outline-secondary');
    btn.classList.add('btn-primary');

    document.getElementById('period_input').value = period;
    document.getElementById('generateBtn').disabled = false;

    const customDates = document.getElementById('custom_dates');
    if (period === 'custom') {
        customDates.style.cssText = 'display: flex !important; flex-wrap: wrap; gap: 1rem;';
    } else {
        customDates.style.cssText = 'display: none !important;';
    }
}
</script>
@endsection
