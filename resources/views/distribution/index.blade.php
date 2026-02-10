@extends('layouts.app')
@section('title', 'سجلات التوزيع')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-truck"></i> سجلات التوزيع</h1>
    <a href="{{ route('distribution.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> توزيع جديد
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($distributions->count() > 0)
        <!-- Mobile view -->
        <div class="d-md-none">
            @foreach($distributions as $dist)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold fs-6">{{ $dist->shop_name }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-person-fill me-1"></i>{{ $dist->driver_name }} •
                            <i class="bi bi-car-front me-1"></i>{{ $dist->vehicle_number }}
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($dist->delivery_date)->format('d/m/Y H:i') }}
                        </div>
                        <div class="mt-1">
                            @foreach($dist->items as $item)
                            <span class="badge bg-light text-dark border me-1">
                                {{ $item->product_name }}: {{ $item->quantity }} {{ $item->unit }}
                            </span>
                            @endforeach
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="fw-bold fs-6" style="color: #7b1fa2;">{{ number_format($dist->total_value, 0) }} جنيه</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('distribution.show', $dist) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-eye-fill"></i> عرض
                    </a>
                    <a href="{{ route('distribution.edit', $dist) }}" class="btn btn-sm btn-outline-success flex-fill">
                        <i class="bi bi-pencil-square"></i> تعديل
                    </a>
                    <form method="POST" action="{{ route('distribution.destroy', $dist) }}" onsubmit="return confirm('هل تريد حذف هذا السجل؟')">
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
                        <th>المحل</th>
                        <th>السائق</th>
                        <th>رقم السيارة</th>
                        <th>المنتجات</th>
                        <th>الإجمالي</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($distributions as $dist)
                    <tr>
                        <td class="fw-bold">{{ $dist->shop_name }}</td>
                        <td>{{ $dist->driver_name }}</td>
                        <td><span class="badge bg-secondary">{{ $dist->vehicle_number }}</span></td>
                        <td>
                            @foreach($dist->items->take(2) as $item)
                            <span class="badge bg-light text-dark border me-1">{{ $item->product_name }}</span>
                            @endforeach
                            @if($dist->items->count() > 2)
                            <span class="text-muted small">+{{ $dist->items->count() - 2 }}</span>
                            @endif
                        </td>
                        <td class="fw-bold" style="color: #7b1fa2;">{{ number_format($dist->total_value, 2) }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($dist->delivery_date)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('distribution.show', $dist) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye-fill"></i> عرض
                                </a>
                                <a href="{{ route('distribution.edit', $dist) }}" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-pencil-square"></i> تعديل
                                </a>
                                <form method="POST" action="{{ route('distribution.destroy', $dist) }}" onsubmit="return confirm('هل تريد حذف هذا السجل؟')">
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
            <i class="bi bi-truck fs-2 mb-3 opacity-25"></i>
            <p class="fs-6">لا توجد سجلات توزيع حتى الآن</p>
            <a href="{{ route('distribution.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> تسجيل أول توزيع
            </a>
        </div>
        @endif
    </div>
    @if($distributions->hasPages())
    <div class="card-footer bg-white">{{ $distributions->links() }}</div>
    @endif
</div>
@endsection