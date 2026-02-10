@extends('layouts.app')
@section('title', 'تعديل سجل جمع الحليب')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square"></i> تعديل سجل جمع الحليب</h1>
    <a href="{{ route('milk.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>


<div class="card">
    <div class="card-body p-4">
        <form action="{{ route('milk.update', $milkCollection->id) }}" method="POST" id="milkForm">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المزارع <span class="text-danger">*</span></label>
                    <input type="text" name="farmer_name"
                        class="form-control @error('farmer_name') is-invalid @enderror"
                        value="{{ old('farmer_name', $milkCollection->farmer_name) }}">
                    @error('farmer_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">اسم السائق <span class="text-danger">*</span></label>
                    <input type="text" name="driver_name"
                        class="form-control @error('driver_name') is-invalid @enderror"
                        value="{{ old('driver_name', $milkCollection->driver_name) }}">
                    @error('driver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">رقم السيارة <span class="text-danger">*</span></label>
                    <input type="text" name="vehicle_number"
                        class="form-control @error('vehicle_number') is-invalid @enderror"
                        value="{{ old('vehicle_number', $milkCollection->vehicle_number) }}">
                    @error('vehicle_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ الجمع <span class="text-danger">*</span></label>
                    <input type="datetime-local" name="collection_date"
                        class="form-control @error('collection_date') is-invalid @enderror"
                        value="{{ old('collection_date', \Carbon\Carbon::parse($milkCollection->collection_date)->format('Y-m-d\TH:i')) }}">
                    @error('collection_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الكمية (لتر) <span class="text-danger">*</span></label>
                    <input type="number" name="quantity_liters" id="quantity"
                        class="form-control @error('quantity_liters') is-invalid @enderror"
                        value="{{ old('quantity_liters', $milkCollection->quantity_liters) }}"
                        step="0.1" min="0.1" oninput="calcTotal()">
                    @error('quantity_liters')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">سعر الليتر (جنيه) <span class="text-danger">*</span></label>
                    <input type="number" name="price_per_liter" id="price"
                        class="form-control @error('price_per_liter') is-invalid @enderror"
                        value="{{ old('price_per_liter', $milkCollection->price_per_liter) }}"
                        step="0.01" min="0.01" oninput="calcTotal()">
                    @error('price_per_liter')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2565AE;">الإجمالي (جنيه)</label>
                    <div class="form-control fw-bold fs-5 text-center" id="total_display"
                        style="background: #e8f0fa; color: #2565AE; border: 2px solid #3a7fd4;">
                        {{ number_format($milkCollection->total_amount, 2) }}
                    </div>
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $milkCollection->notes) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i> حفظ التعديلات
                </button>
                <a href="{{ route('milk.index') }}" class="btn btn-outline-secondary btn-lg">إلغاء</a>
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
        document.getElementById('total_display').textContent = (qty * price).toFixed(2);
    }
</script>
@endsection