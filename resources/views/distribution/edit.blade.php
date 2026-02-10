@extends('layouts.app')
@section('title', 'تعديل سجل التوزيع')

@section('content')
<div class="page-header">
    <h1><i class="bi bi-pencil-square"></i> تعديل سجل التوزيع</h1>
    <a href="{{ route('distribution.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-right me-1"></i> رجوع
    </a>
</div>

<form method="POST" action="{{ route('distribution.update', $distribution) }}" id="distForm">
    @csrf @method('PUT')
    <div class="row g-3">
        <!-- Main Info -->
        <div class="col-12">
            <div class="card">
                <div class="card-header"><i class="bi bi-shop-window"></i> معلومات التوصيل</div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">اسم المحل <span class="text-danger">*</span></label>
                            <input type="text" name="shop_name"
                                class="form-control @error('shop_name') is-invalid @enderror"
                                value="{{ old('shop_name', $distribution->shop_name) }}"
                                placeholder="اسم المحل أو الزبون">
                            @error('shop_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">تاريخ التوصيل <span class="text-danger">*</span></label>
                            <input type="datetime-local" name="delivery_date"
                                class="form-control @error('delivery_date') is-invalid @enderror"
                                value="{{ old('delivery_date', \Carbon\Carbon::parse($distribution->delivery_date)->format('Y-m-d\TH:i')) }}">
                            @error('delivery_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">اسم السائق <span class="text-danger">*</span></label>
                            <input type="text" name="driver_name"
                                class="form-control @error('driver_name') is-invalid @enderror"
                                value="{{ old('driver_name', $distribution->driver_name) }}"
                                placeholder="اسم السائق">
                            @error('driver_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">رقم السيارة <span class="text-danger">*</span></label>
                            <input type="text" name="vehicle_number"
                                class="form-control @error('vehicle_number') is-invalid @enderror"
                                value="{{ old('vehicle_number', $distribution->vehicle_number) }}"
                                placeholder="رقم لوحة السيارة">
                            @error('vehicle_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">ملاحظات (اختياري)</label>
                            <textarea name="notes" class="form-control" rows="2">{{ old('notes', $distribution->notes) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <span><i class="bi bi-boxes"></i> المنتجات الموزعة</span>
                    <button type="button" class="btn btn-success btn-sm" onclick="addItem()">
                        <i class="bi bi-plus-lg me-1"></i> إضافة منتج
                    </button>
                </div>
                <div class="card-body">
                    @error('items')
                    <div class="alert alert-danger py-2">{{ $message }}</div>
                    @enderror

                    <div id="items-container">
                        <!-- Existing items loaded by JS -->
                    </div>

                    <!-- Total -->
                    <div class="d-flex justify-content-end mt-3">
                        <div class="p-3 rounded-3 text-center" style="background: #e8f0fa; min-width: 200px;">
                            <div class="text-muted small fw-bold mb-1">الإجمالي الكلي</div>
                            <div class="fw-bold fs-4" style="color: #2565AE;" id="grand_total">0.00 جنيه</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-12">
            <div class="d-flex gap-3">
                <button type="submit" class="btn btn-primary btn-lg flex-fill">
                    <i class="bi bi-floppy-fill me-2"></i> حفظ التعديلات
                </button>
                <a href="{{ route('distribution.index') }}" class="btn btn-outline-secondary btn-lg">
                    <i class="bi bi-x-lg me-1"></i> إلغاء
                </a>
            </div>
        </div>
    </div>
</form>
@endsection

@section('scripts')
<script>
    let itemCount = 0;

    // Existing items from server
    const existingItems = @json($distribution->items);

    function addItem(productName = '', quantity = '', unit = 'وحدة', unitPrice = '', subtotal = 0) {
        const container = document.getElementById('items-container');
        const idx = itemCount++;
        const unitOptions = ['وحدة', 'لتر', 'كيلو', 'كرتون', 'علبة'];
        const optionsHtml = unitOptions.map(u =>
            `<option value="${u}" ${u === unit ? 'selected' : ''}>${u}</option>`
        ).join('');

        const html = `
        <div class="item-row" id="item-${idx}">
            <div class="row g-2 align-items-end">
                <div class="col-12 col-md-4">
                    <label class="form-label small fw-bold">اسم المنتج</label>
                    <input type="text" name="items[${idx}][product_name]"
                           class="form-control form-control-sm"
                           placeholder="مثال: حليب كامل الدسم"
                           value="${productName}" required>
                </div>
                <div class="col-4 col-md-2">
                    <label class="form-label small fw-bold">الكمية</label>
                    <input type="number" name="items[${idx}][quantity]"
                           class="form-control form-control-sm qty-input"
                           placeholder="0" step="0.1" min="0.1" required
                           value="${quantity}"
                           oninput="calcItemSubtotal(${idx})">
                </div>
                <div class="col-4 col-md-2">
                    <label class="form-label small fw-bold">الوحدة</label>
                    <select name="items[${idx}][unit]" class="form-select form-select-sm">
                        ${optionsHtml}
                    </select>
                </div>
                <div class="col-4 col-md-2">
                    <label class="form-label small fw-bold">سعر الوحدة</label>
                    <input type="number" name="items[${idx}][unit_price]"
                           class="form-control form-control-sm price-input"
                           placeholder="0.00" step="0.01" min="0" required
                           value="${unitPrice}"
                           oninput="calcItemSubtotal(${idx})">
                </div>
                <div class="col-8 col-md-1">
                    <label class="form-label small fw-bold" style="color: #2565AE;">الإجمالي</label>
                    <div class="form-control form-control-sm text-center fw-bold subtotal-display"
                         id="sub-${idx}"
                         style="background: #e8f0fa; color: #2565AE;">${parseFloat(subtotal).toFixed(2)}</div>
                </div>
                <div class="col-4 col-md-1 text-center">
                    <label class="form-label small d-none d-md-block">&nbsp;</label>
                    <button type="button" class="btn btn-sm btn-outline-danger w-100"
                            onclick="removeItem(${idx})">
                        <i class="bi bi-trash-fill"></i>
                    </button>
                </div>
            </div>
        </div>`;
        container.insertAdjacentHTML('beforeend', html);
        updateGrandTotal();
    }

    function removeItem(idx) {
        const el = document.getElementById('item-' + idx);
        if (el) el.remove();
        updateGrandTotal();
    }

    function calcItemSubtotal(idx) {
        const row = document.getElementById('item-' + idx);
        if (!row) return;
        const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
        const price = parseFloat(row.querySelector('.price-input').value) || 0;
        const sub = qty * price;
        document.getElementById('sub-' + idx).textContent = sub.toFixed(2);
        updateGrandTotal();
    }

    function updateGrandTotal() {
        let total = 0;
        document.querySelectorAll('.subtotal-display').forEach(el => {
            total += parseFloat(el.textContent) || 0;
        });
        document.getElementById('grand_total').textContent = total.toFixed(2) + ' جنيه';
    }

    // Load existing items on page load
    existingItems.forEach(item => {
        addItem(item.product_name, item.quantity, item.unit, item.unit_price, item.subtotal);
    });
</script>
@endsection
