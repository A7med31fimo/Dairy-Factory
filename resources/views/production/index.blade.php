@extends('layouts.app')
@section('title', 'سجلات الإنتاج')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-boxes"></i> سجلات الإنتاج</h1>
    <a href="{{ route('production.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> تسجيل إنتاج جديد
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($productions->count() > 0)
        <!-- Mobile -->
        <div class="d-md-none">
            @foreach($productions as $prod)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold fs-6">{{ $prod->product_name }}</div>
                        <div>
                            <span class="badge bg-info text-white">
                                {{ $productTypes[$prod->product_type] ?? $prod->product_type }}
                            </span>
                        </div>
                        <div class="text-muted small mt-1">
                            <i class="bi bi-calendar me-1"></i>{{ $prod->production_date->format('d/m/Y') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-5" style="color: #e67e22;">{{ number_format($prod->quantity, 1) }}</div>
                        <div class="text-muted small">{{ $prod->unit }}</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('production.edit', $prod) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil-square"></i> تعديل
                    </a>
                    <form method="POST" action="{{ route('production.destroy', $prod) }}" onsubmit="return confirm('حذف؟')">
                        @csrf @method('DELETE')
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash-fill"></i>حذف</button>
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
                        <th>اسم المنتج</th>
                        <th>النوع</th>
                        <th>الكمية</th>
                        <th>الوحدة</th>
                        <th>تاريخ الإنتاج</th>
                        <th>ملاحظات</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($productions as $prod)
                    <tr>
                        <td class="fw-bold">{{ $prod->product_name }}</td>
                        <td><span class="badge bg-info">{{ $productTypes[$prod->product_type] ?? $prod->product_type }}</span></td>
                        <td class="fw-bold" style="color: #e67e22;">{{ number_format($prod->quantity, 1) }}</td>
                        <td>{{ $prod->unit }}</td>
                        <td>{{ $prod->production_date->format('d/m/Y') }}</td>
                        <td class="small text-muted">{{ \Str::limit($prod->notes, 25) ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('production.edit', $prod) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>تعديل
                                </a>
                                <form method="POST" action="{{ route('production.destroy', $prod) }}" onsubmit="return confirm('حذف؟')">
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
            <i class="bi bi-boxes fs-2 mb-3 opacity-25"></i>
            <p>لا توجد سجلات إنتاج</p>
            <a href="{{ route('production.create') }}" class="btn btn-primary">تسجيل أول إنتاج</a>
        </div>
        @endif
    </div>
    @if($productions->hasPages())
    <div class="card-footer bg-white">{{ $productions->links() }}</div>
    @endif
</div>
@endsection
