@extends('layouts.app')
@section('title', 'تفاصيل الدين')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text-fill"></i> تفاصيل الدين</h1>
    <a href="{{ route('debts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<div class="row g-3">
    <!-- Debt Info -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header"><i class="bi bi-info-circle-fill"></i> معلومات الدين</div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="text-muted small">اسم المدين</div>
                    <div class="fw-bold fs-5">{{ $debt->debtor_name }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">سبب الدين</div>
                    <div class="fw-bold">{{ $debt->reason }}</div>
                </div>
                <div class="mb-3">
                    <div class="text-muted small">تاريخ الدين</div>
                    <div class="fw-bold">{{ $debt->debt_date->format('d/m/Y') }}</div>
                </div>

                <hr>

                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="p-2 rounded-3" style="background: #ffebee;">
                            <div class="small text-muted">الإجمالي</div>
                            <div class="fw-bold" style="color: #e53935;">{{ number_format($debt->total_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-3" style="background: #e8f0fa;">
                            <div class="small text-muted">المدفوع</div>
                            <div class="fw-bold text-success">{{ number_format($debt->paid_amount, 2) }}</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="p-2 rounded-3" style="background: #fff8e1;">
                            <div class="small text-muted">المتبقي</div>
                            <div class="fw-bold text-warning">{{ number_format($debt->remaining_amount, 2) }}</div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 text-center">
                    <span class="badge bg-{{ $debt->status_color }} fs-6 px-4 py-2">
                        {{ $debt->status_label }}
                    </span>
                </div>

                @if($debt->notes)
                <div class="mt-3 p-2 rounded-3 small" style="background: #f8f9fa;">
                    <strong>ملاحظات:</strong> {{ $debt->notes }}
                </div>
                @endif
            </div>
        </div>

        <!-- Add Payment -->
        @if($debt->status !== 'paid')
        <div class="card mt-3">
            <div class="card-header"><i class="bi bi-cash-wave"></i> تسجيل دفعة جديدة</div>
            <div class="card-body">
                <form method="POST" action="{{ route('debts.payment', $debt) }}">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">مبلغ الدفعة <span class="text-danger">*</span></label>
                        <input type="number" name="amount"
                            class="form-control @error('amount') is-invalid @enderror"
                            step="0.01" min="0.01"
                            max="{{ $debt->remaining_amount }}"
                            placeholder="0.00">
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        <div class="form-text">الحد الأقصى: {{ number_format($debt->remaining_amount, 2) }} جنيه</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">تاريخ الدفع <span class="text-danger">*</span></label>
                        <input type="date" name="payment_date"
                            class="form-control @error('payment_date') is-invalid @enderror"
                            value="{{ date('Y-m-d') }}">
                        @error('payment_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">ملاحظات</label>
                        <input type="text" name="notes" class="form-control" placeholder="اختياري">
                    </div>
                    <button type="submit" class="btn btn-success w-100">
                        <i class="bi bi-check me-2"></i> تسجيل الدفعة
                    </button>
                </form>
            </div>
        </div>
        @endif
    </div>

    <!-- Payment History -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header"><i class="bi bi-clock-history"></i> سجل الدفعات</div>
            <div class="card-body p-0">
                @if($debt->payments->count() > 0)
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>المبلغ</th>
                            <th>التاريخ</th>
                            <th>ملاحظات</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($debt->payments->sortByDesc('payment_date') as $i => $payment)
                        <tr>
                            <td>{{ $i + 1 }}</td>
                            <td class="fw-bold text-success">{{ number_format($payment->amount, 2) }} جنيه</td>
                            <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                            <td class="small text-muted">{{ $payment->notes ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: #e8f0fa;">
                            <td class="fw-bold" colspan="3">إجمالي المدفوع</td>
                            <td class="fw-bold text-success">{{ number_format($debt->paid_amount, 2) }} جنيه</td>
                        </tr>
                    </tfoot>
                </table>
                @else
                <div class="text-center py-4 text-muted">
                    <i class="bi bi-inbox-fill fs-4 mb-2 opacity-25"></i>
                    <p>لا توجد دفعات مسجلة</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection