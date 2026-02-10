@extends('layouts.app')
@section('title', 'تسجيل جمع حليب جديد')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-droplet-fill"></i> تسجيل جمع حليب جديد</h1>
    <a href="{{ route('milk.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('milk.store') }}" id="milkForm">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-person-fill me-1" style="color: #2565AE;"></i>
                        اسم المزارع <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="farmer_name"
                        class="form-control @error('farmer_name') is-invalid @enderror"
                        value="{{ old('farmer_name') }}"
                        placeholder="مثال: أحمد محمد">
                    @error('farmer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-person-badge-fill me-1" style="color: #2565AE;"></i>
                        اسم السائق <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="driver_name"
                        class="form-control @error('driver_name') is-invalid @enderror"
                        value="{{ old('driver_name') }}"
                        placeholder="مثال: علي سعيد">
                    @error('driver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-car-front me-1" style="color: #2565AE;"></i>
                        رقم السيارة <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="vehicle_number"
                        class="form-control @error('vehicle_number') is-invalid @enderror"
                        value="{{ old('vehicle_number') }}"
                        placeholder="مثال: أ ب ج 1234">
                    @error('vehicle_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-calendar me-1" style="color: #2565AE;"></i>
                        تاريخ ووقت الجمع <span class="text-danger">*</span>
                    </label>
                    <input type="datetime-local" name="collection_date"
                        class="form-control @error('collection_date') is-invalid @enderror"
                        value="{{ old('collection_date', now()->format('Y-m-d\TH:i')) }}">
                    @error('collection_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <!-- Quantity & Price row -->
                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-flask me-1" style="color: #2565AE;"></i>
                        الكمية (لتر) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="quantity_liters" id="quantity"
                        class="form-control @error('quantity_liters') is-invalid @enderror"
                        value="{{ old('quantity_liters') }}"
                        step="0.1" min="0.1"
                        placeholder="0.0"
                        oninput="calcTotal()">
                    @error('quantity_liters')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-tag-fill me-1" style="color: #2565AE;"></i>
                        سعر الليتر (جنيه) <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="price_per_liter" id="price"
                        class="form-control @error('price_per_liter') is-invalid @enderror"
                        value="{{ old('price_per_liter') }}"
                        step="0.01" min="0.01"
                        placeholder="0.00"
                        oninput="calcTotal()">
                    @error('price_per_liter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2565AE;">
                        <i class="bi bi-cash me-1"></i>
                        الإجمالي (جنيه)
                    </label>
                    <div class="form-control fw-bold fs-5 text-center"
                        id="total_display"
                        style="background: #e8f0fa; color: #2565AE; border: 2px solid #3a7fd4;">
                        0.00
                    </div>
                </div>

                <div class="col-12">
                    <label class="form-label">
                        <i class="bi bi-sticky-fill me-1" style="color: #777;"></i>
                        ملاحظات (اختياري)
                    </label>
                    <textarea name="notes" class="form-control" rows="2"
                        placeholder="أي ملاحظات إضافية...">{{ old('notes') }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-3 flex-wrap">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i>
                    حفظ السجل
                </button>
                <a href="{{ route('milk.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-lg me-1"></i> إلغاء
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function calcTotal() {
        const qty = parseFloat(document.getElementById('quantity').value) || 0;
        const price = parseFloat(document.getElementById('price').value) || 0;
        const total = qty * price;
        document.getElementById('total_display').textContent = total.toFixed(2);
    }
</script>
@endsection