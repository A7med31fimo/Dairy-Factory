@extends('layouts.app')
@section('title', 'سجلات الديون')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-file-earmark-text-fill"></i> سجلات الديون</h1>
    <a href="{{ route('debts.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> تسجيل دين جديد
    </a>
</div>

<!-- Total outstanding -->
<div class="alert border-0 mb-3 d-flex align-items-center gap-3" style="background: #ffebee; border-radius: 12px;">
    <i class="bi bi-exclamation-triangle-fill fs-5" style="color: #e53935;"></i>
    <div>
        <strong>إجمالي الديون غير المسددة:</strong>
        <span class="fs-5 fw-bold ms-2" style="color: #e53935;">{{ number_format($totalUnpaid, 2) }} جنيه</span>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($debts->count() > 0)
        <!-- Mobile view -->
        <div class="d-md-none">
            @foreach($debts as $debt)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold fs-6">{{ $debt->debtor_name }}</div>
                        <div class="text-muted small">{{ $debt->reason }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>{{ $debt->debt_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <span class="badge bg-{{ $debt->status_color }}">{{ $debt->status_label }}</span>
                        <div class="fw-bold mt-1">{{ number_format($debt->total_amount, decimals: 0) }} جنيه</div>
                        @if($debt->paid_amount > 0)
                        <div class="small text-success">مدفوع: {{ number_format($debt->paid_amount, 0) }}</div>
                        @endif
                    </div>
                </div>
                @if($debt->remaining_amount > 0)
                <div class="small text-danger fw-bold mb-2">
                    المتبقي: {{ number_format($debt->remaining_amount, 0) }} جنيه
                </div>
                @endif
                <div class="d-flex gap-2">
                    <a href="{{ route('debts.show', $debt) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye-fill"></i> عرض
                    </a>
                    <a href="{{ route('debts.edit', $debt) }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-pencil-square"></i> تعديل
                    </a>
                    <form method="POST" action="{{ route('debts.destroy', $debt) }}" onsubmit="return confirm('هل تريد حذف؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash-fill"></i> حذف
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop view -->
        <div class="d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>اسم المدين</th>
                        <th>السبب</th>
                        <th>الإجمالي</th>
                        <th>المدفوع</th>
                        <th>المتبقي</th>
                        <th>الحالة</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($debts as $debt)
                    <tr>
                        <td class="fw-bold">{{ $debt->debtor_name }}</td>
                        <td class="small">{{ \Str::limit($debt->reason, 30) }}</td>
                        <td class="fw-bold">{{ number_format($debt->total_amount, 2) }}</td>
                        <td class="text-success fw-bold">{{ number_format($debt->paid_amount, 2) }}</td>
                        <td class="text-danger fw-bold">{{ number_format($debt->remaining_amount, 2) }}</td>
                        <td><span class="badge bg-{{ $debt->status_color }}">{{ $debt->status_label }}</span></td>
                        <td class="small">{{ $debt->debt_date->format('d/m/Y') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('debts.show', $debt) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye-fill"></i> عرض
                                </a>
                                <a href="{{ route('debts.edit', $debt) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil-square"></i> تعديل
                                </a> 
                                <form method="POST" action="{{ route('debts.destroy', $debt) }}" onsubmit="return confirm('هل تريد حذف؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash-fill"></i> حذف
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @else
        <div class="text-center py-5 text-muted">
            <i class="bi bi-file-earmark-text-fill fs-2 mb-3 opacity-25"></i>
            <p class="fs-6">لا توجد سجلات ديون</p>
            <a href="{{ route('debts.create') }}" class="btn btn-primary">إضافة سجل</a>
        </div>
        @endif
    </div>
    @if($debts->hasPages())
    <div class="card-footer bg-white">{{ $debts->links() }}</div>
    @endif
</div>
@endsection