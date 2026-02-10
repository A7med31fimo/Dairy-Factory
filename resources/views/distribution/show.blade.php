@extends('layouts.app')
@section('title', 'تفاصيل التوزيع')

@section('content')
<div class="page-header no-print">
    <h1><i class="bi bi-truck"></i> تفاصيل عملية التوزيع</h1>
    <div class="d-flex gap-2">
        <button onclick="window.print()" class="btn btn-outline-primary">
            <i class="bi bi-printer-fill me-1"></i> طباعة
        </button>
        <a href="{{ route('distribution.edit', $distribution) }}" class="btn btn-outline-success">
            <i class="bi bi-pencil-square me-1"></i> تعديل
        </a>
        <a href="{{ route('distribution.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-right me-1"></i> رجوع
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body p-4">
        <div class="row g-3 mb-4">
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8f9fa;">
                    <div class="text-muted small">اسم المحل</div>
                    <div class="fw-bold">{{ $distribution->shop_name }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8f9fa;">
                    <div class="text-muted small">السائق</div>
                    <div class="fw-bold">{{ $distribution->driver_name }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8f9fa;">
                    <div class="text-muted small">رقم السيارة</div>
                    <div class="fw-bold">{{ $distribution->vehicle_number }}</div>
                </div>
            </div>
            <div class="col-6 col-md-3">
                <div class="p-3 rounded-3" style="background: #f8f9fa;">
                    <div class="text-muted small">تاريخ التوصيل</div>
                    <div class="fw-bold">{{ \Carbon\Carbon::parse($distribution->delivery_date)->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <h6 class="fw-bold mb-3">
            <i class="bi bi-boxes me-2" style="color: #2565AE;"></i>
            المنتجات الموزعة
        </h6>
        <table class="table table-bordered">
            <thead style="background: #e8f0fa;">
                <tr>
                    <th>#</th>
                    <th>المنتج</th>
                    <th>الكمية</th>
                    <th>الوحدة</th>
                    <th>سعر الوحدة</th>
                    <th>الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distribution->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td class="fw-bold">{{ $item->product_name }}</td>
                    <td>{{ number_format($item->quantity, 1) }}</td>
                    <td>{{ $item->unit }}</td>
                    <td>{{ number_format($item->unit_price, 2) }}</td>
                    <td class="fw-bold" style="color: #2565AE;">{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #e8f0fa;">
                    <td colspan="5" class="text-end fw-bold fs-6">الإجمالي الكلي:</td>
                    <td class="fw-bold fs-5" style="color: #2565AE;">{{ number_format($distribution->total_value, decimals: 2) }} جنيه</td>
                </tr>
            </tfoot>
        </table>

        @if($distribution->notes)
        <div class="mt-3 p-3 rounded-3" style="background: #fff8e1;">
            <strong>ملاحظات:</strong> {{ $distribution->notes }}
        </div>
        @endif
    </div>
</div>
@endsection