@extends('layouts.app')
@section('title', 'تسجيل إنتاج جديد')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-boxes"></i> تسجيل إنتاج جديد</h1>
    <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('production.store') }}">
            @csrf
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-tag-fill me-1" style="color: #e67e22;"></i>
                        نوع المنتج <span class="text-danger">*</span>
                    </label>
                    <select name="product_type" class="form-select @error('product_type') is-invalid @enderror"
                            onchange="updateProductName(this)">
                        <option value="">-- اختر النوع --</option>
                        @foreach($productTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('product_type') == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-6">
                    <label class="form-label">
                        <i class="bi bi-box me-1" style="color: #e67e22;"></i>
                        اسم المنتج <span class="text-danger">*</span>
                    </label>
                    <input type="text" name="product_name" id="product_name"
                           class="form-control @error('product_name') is-invalid @enderror"
                           value="{{ old('product_name') }}"
                           placeholder="مثال: حليب كامل الدسم 1 لتر">
                    @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-grid-3x3-gap-fill me-1" style="color: #e67e22;"></i>
                        الكمية المنتجة <span class="text-danger">*</span>
                    </label>
                    <input type="number" name="quantity"
                           class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity') }}"
                           step="0.1" min="0.1"
                           placeholder="0.0">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-bank2 me-1" style="color: #e67e22;"></i>
                        الوحدة <span class="text-danger">*</span>
                    </label>
                    <select name="unit" class="form-select @error('unit') is-invalid @enderror">
                        <option value="لتر" {{ old('unit') == 'لتر' ? 'selected' : '' }}>لتر</option>
                        <option value="كيلو" {{ old('unit') == 'كيلو' ? 'selected' : '' }}>كيلو</option>
                        <option value="وحدة" {{ old('unit') == 'وحدة' ? 'selected' : '' }}>وحدة</option>
                        <option value="كرتون" {{ old('unit') == 'كرتون' ? 'selected' : '' }}>كرتون</option>
                        <option value="علبة" {{ old('unit') == 'علبة' ? 'selected' : '' }}>علبة</option>
                    </select>
                    @error('unit')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-md-4">
                    <label class="form-label">
                        <i class="bi bi-calendar me-1" style="color: #e67e22;"></i>
                        تاريخ الإنتاج <span class="text-danger">*</span>
                    </label>
                    <input type="date" name="production_date"
                           class="form-control @error('production_date') is-invalid @enderror"
                           value="{{ old('production_date', date('Y-m-d')) }}">
                    @error('production_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="col-12">
                    <label class="form-label">ملاحظات (اختياري)</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes') }}</textarea>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i> حفظ
                </button>
                <a href="{{ route('production.index') }}" class="btn btn-outline-secondary btn-lg">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
const defaultNames = {
    milk: 'حليب طازج',
    yogurt: 'زبادي',
    butter: 'زبدة',
    cheese: 'جبن',
    cream: 'قشدة',
    other: ''
};
function updateProductName(sel) {
    const nameField = document.getElementById('product_name');
    if (defaultNames[sel.value]) {
        nameField.value = defaultNames[sel.value];
    }
}
</script>
@endsection
