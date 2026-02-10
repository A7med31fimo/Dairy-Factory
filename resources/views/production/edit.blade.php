@extends('layouts.app')
@section('title', 'تعديل سجل الإنتاج')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square"></i> تعديل سجل الإنتاج</h1>
    <a href="{{ route('production.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('production.update', $production) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">نوع المنتج <span class="text-danger">*</span></label>
                    <select name="product_type" class="form-select @error('product_type') is-invalid @enderror">
                        @foreach($productTypes as $key => $label)
                        <option value="{{ $key }}" {{ old('product_type', $production->product_type) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('product_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                    <input type="text" name="product_name"
                           class="form-control @error('product_name') is-invalid @enderror"
                           value="{{ old('product_name', $production->product_name) }}">
                    @error('product_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الكمية <span class="text-danger">*</span></label>
                    <input type="number" name="quantity"
                           class="form-control @error('quantity') is-invalid @enderror"
                           value="{{ old('quantity', $production->quantity) }}"
                           step="0.1" min="0.1">
                    @error('quantity')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-4">
                    <label class="form-label">الوحدة <span class="text-danger">*</span></label>
                    <select name="unit" class="form-select">
                        @foreach(['لتر','كيلو','وحدة','كرتون','علبة'] as $u)
                        <option value="{{ $u }}" {{ old('unit', $production->unit) == $u ? 'selected' : '' }}>{{ $u }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">تاريخ الإنتاج <span class="text-danger">*</span></label>
                    <input type="date" name="production_date"
                           class="form-control @error('production_date') is-invalid @enderror"
                           value="{{ old('production_date', $production->production_date->format('Y-m-d')) }}">
                    @error('production_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $production->notes) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i> حفظ التعديلات
                </button>
                <a href="{{ route('production.index') }}" class="btn btn-outline-secondary btn-lg">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection
