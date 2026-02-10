@extends('layouts.app')
@section('title', 'تعديل المصروف')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square"></i> تعديل المصروف</h1>
    <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('expenses.update', $expense) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">التصنيف <span class="text-danger">*</span></label>
                    <select name="category" class="form-select @error('category') is-invalid @enderror">
                        <option value="">-- اختر التصنيف --</option>
                        @foreach($categories as $key => $label)
                        <option value="{{ $key }}" {{ old('category', $expense->category) == $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                        @endforeach
                    </select>
                    @error('category')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">المبلغ (جنيه) <span class="text-danger">*</span></label>
                    <input type="number" name="amount"
                        class="form-control @error('amount') is-invalid @enderror"
                        value="{{ old('amount', $expense->amount) }}"
                        step="0.01" min="0.01">
                    @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">التاريخ <span class="text-danger">*</span></label>
                    <input type="date" name="expense_date"
                        class="form-control @error('expense_date') is-invalid @enderror"
                        value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}">
                    @error('expense_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $expense->notes) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i> حفظ التعديلات
                </button>
                <a href="{{ route('expenses.index') }}" class="btn btn-outline-secondary btn-lg">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection