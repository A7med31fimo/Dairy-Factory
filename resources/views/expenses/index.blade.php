@extends('layouts.app')
@section('title', 'سجلات المصروفات')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-wallet2"></i> سجلات المصروفات</h1>
    <a href="{{ route('expenses.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> إضافة مصروف
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($expenses->count() > 0)
        <!-- Mobile -->
        <div class="d-md-none">
            @foreach($expenses as $expense)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-center mb-2">
                    <div>
                        <div class="fw-bold">{{ $categories[$expense->category] ?? $expense->category }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>{{ $expense->expense_date->format('d/m/Y') }}
                        </div>
                        @if($expense->notes)
                        <div class="text-muted small">{{ $expense->notes }}</div>
                        @endif
                    </div>
                    <div class="fw-bold fs-5" style="color: #e67e22;">{{ number_format($expense->amount, 2) }}</div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil-square"></i> تعديل
                    </a>
                    <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('حذف؟')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i> حذف</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop -->
        <div class="d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>التصنيف</th>
                        <th>المبلغ</th>
                        <th>التاريخ</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expenses as $expense)
                    <tr>
                        <td>
                            <span class="badge bg-warning text-dark">
                                {{ $categories[$expense->category] ?? $expense->category }}
                            </span>
                        </td>
                        <td class="fw-bold" style="color: #e67e22;">{{ number_format($expense->amount, 2) }}</td>
                        <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                        <td class="small text-muted">{{ $expense->notes ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('expenses.edit', $expense) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i> تعديل
                                </a>
                                <form method="POST" action="{{ route('expenses.destroy', $expense) }}" onsubmit="return confirm('حذف؟')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i> حذف</button>
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
            <i class="bi bi-wallet2 fs-2 mb-3 opacity-25"></i>
            <p>لا توجد مصروفات مسجلة</p>
            <a href="{{ route('expenses.create') }}" class="btn btn-primary">إضافة مصروف</a>
        </div>
        @endif
    </div>
    @if($expenses->hasPages())
    <div class="card-footer bg-white">{{ $expenses->links() }}</div>
    @endif
</div>
@endsection
