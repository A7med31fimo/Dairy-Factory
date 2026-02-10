@extends('layouts.app')
@section('title', 'تعديل سجل الدين')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square"></i> تعديل سجل الدين</h1>
    <a href="{{ route('debts.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<div class="card">
    <div class="card-body p-4">
        <form method="POST" action="{{ route('debts.update', $debt) }}">
            @csrf @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">اسم المدين <span class="text-danger">*</span></label>
                    <input type="text" name="debtor_name"
                        class="form-control @error('debtor_name') is-invalid @enderror"
                        value="{{ old('debtor_name', $debt->debtor_name) }}">
                    @error('debtor_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">تاريخ الدين <span class="text-danger">*</span></label>
                    <input type="date" name="debt_date"
                        class="form-control @error('debt_date') is-invalid @enderror"
                        value="{{ old('debt_date', $debt->debt_date->format('Y-m-d')) }}">
                    @error('debt_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">المبلغ الإجمالي (جنيه) <span class="text-danger">*</span></label>
                    <input type="number" name="total_amount"
                        class="form-control @error('total_amount') is-invalid @enderror"
                        value="{{ old('total_amount', $debt->total_amount) }}"
                        step="0.01" min="0.01">
                    @error('total_amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">سبب الدين <span class="text-danger">*</span></label>
                    <textarea name="reason" class="form-control @error('reason') is-invalid @enderror" rows="2">{{ old('reason', $debt->reason) }}</textarea>
                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">ملاحظات</label>
                    <textarea name="notes" class="form-control" rows="2">{{ old('notes', $debt->notes) }}</textarea>
                </div>
            </div>
            <hr class="my-4">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i> حفظ التعديلات
                </button>
                <a href="{{ route('debts.index') }}" class="btn btn-outline-secondary btn-lg">إلغاء</a>
            </div>
        </form>
    </div>
</div>
@endsection