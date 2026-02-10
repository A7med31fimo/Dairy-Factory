@extends('layouts.app')
@section('title', 'جمع الحليب')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-droplet-fill"></i> سجلات جمع الحليب</h1>
    <a href="{{ route('milk.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i> تسجيل جمع جديد
    </a>
</div>

<div class="card">
    <div class="card-body p-0">
        @if($collections->count() > 0)
        <!-- Mobile view (cards) -->
        <div class="d-md-none">
            @foreach($collections as $milk)
            <div class="p-3 border-bottom">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <div>
                        <div class="fw-bold fs-6">{{ $milk->farmer_name }}</div>
                        <div class="text-muted small">
                            <i class="bi bi-person-fill me-1"></i>{{ $milk->driver_name }} •
                            <i class="bi bi-car-front me-1"></i>{{ $milk->vehicle_number }}
                        </div>
                        <div class="text-muted small">
                            <i class="bi bi-calendar me-1"></i>
                            {{ \Carbon\Carbon::parse($milk->collection_date)->format('d/m/Y H:i') }}
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="badge bg-success mb-1">{{ number_format($milk->quantity_liters, 1) }} لتر</div>
                        <div class="fw-bold" style="color: #2565AE;">{{ number_format($milk->total_amount, 2) }} جنيه</div>
                    </div>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('milk.edit', $milk) }}" class="btn btn-sm btn-outline-primary flex-fill">
                        <i class="bi bi-pencil-square"></i> تعديل
                    </a>
                    <form method="POST" action="{{ route('milk.destroy', $milk) }}" onsubmit="return confirm('هل تريد حذف هذا السجل؟')">
                        @csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">
                            <i class="bi bi-trash-fill"></i>
                        </button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Desktop view (table) -->
        <div class="d-none d-md-block">
            <table class="table table-hover mb-0">
                <thead>
                    <tr>
                        <th>المزارع</th>
                        <th>السائق</th>
                        <th>رقم السيارة</th>
                        <th>الكمية (لتر)</th>
                        <th>سعر الليتر</th>
                        <th>الإجمالي</th>
                        <th>التاريخ</th>
                        <th>إجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($collections as $milk)
                    <tr>
                        <td class="fw-bold">{{ $milk->farmer_name }}</td>
                        <td>{{ $milk->driver_name }}</td>
                        <td><span class="badge bg-secondary">{{ $milk->vehicle_number }}</span></td>
                        <td><span class="badge bg-success">{{ number_format($milk->quantity_liters, 1) }}</span></td>
                        <td>{{ number_format($milk->price_per_liter, 2) }}</td>
                        <td class="fw-bold" style="color: #2565AE;">{{ number_format($milk->total_amount, 2) }}</td>
                        <td class="small">{{ \Carbon\Carbon::parse($milk->collection_date)->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="d-flex gap-1">
                                <a href="{{ route('milk.edit', $milk) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil-square"></i>تعديل
                                </a>
                                <form method="POST" action="{{ route('milk.destroy', $milk) }}" onsubmit="return confirm('هل تريد حذف هذا السجل؟')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fa-solid bi-trash-fill"></i> حذف
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
            <i class="bi bi-droplet-fill fs-2 mb-3 opacity-25"></i>
            <p class="fs-6">لا توجد سجلات حتى الآن</p>
            <a href="{{ route('milk.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-lg me-1"></i> تسجيل أول عملية جمع
            </a>
        </div>
        @endif
    </div>
    @if($collections->hasPages())
    <div class="card-footer bg-white">
        {{ $collections->links() }}
    </div>
    @endif
</div>
@endsection