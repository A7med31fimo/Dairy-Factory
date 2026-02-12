<!DOCTYPE html>
<html dir="rtl" lang="ar">

<head>
    <meta charset="UTF-8">
    <title>تقرير مصنع الألبان</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            direction: rtl;
            text-align: right;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2565AE;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }

        .header h1 {
            font-size: 20px;
            color: #2565AE;
            margin: 0 0 5px 0;
            font-weight: bold;
        }

        .header h2 {
            font-size: 16px;
            color: #555;
            margin: 0 0 5px 0;
        }

        .header p {
            font-size: 10px;
            color: #777;
            margin: 2px 0;
        }

        .summary-row {
            margin-bottom: 20px;
        }

        .summary-box {
            display: inline-block;
            width: 50%;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px 8px;
            margin: 0 1%;
            text-align: center;
            background: #f9f9f9;
            vertical-align: top;
            margin-bottom: 10px;
        }

        .summary-value {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 4px;
        }

        .summary-label {
            font-size: 9px;
            color: #777;
        }

        .section {
            margin-bottom: 20px;
        }

        .section-title {
            background: #2565AE;
            color: white;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10px;
            margin-bottom: 10px;
        }

        th {
            background: #e8f0fa;
            padding: 6px 8px;
            border: 1px solid #ccc;
            font-weight: bold;
            text-align: right;
        }

        td {
            padding: 5px 8px;
            border: 1px solid #ddd;
            text-align: right;
        }

        tr:nth-child(even) td {
            background: #f9f9f9;
        }

        .tfoot-row td {
            background: #e8f0fa;
            font-weight: bold;
        }

        .net-balance {
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #2565AE;
            border-radius: 8px;
            background: #e8f0fa;
            text-align: center;
        }

        .net-balance .label {
            font-size: 12px;
            color: #555;
            margin-bottom: 6px;
        }

        .net-balance .value {
            font-size: 20px;
            font-weight: bold;
        }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 9px;
            color: #999;
            text-align: center;
        }

        .no-data {
            padding: 10px;
            color: #999;
            text-align: center;
        }
    </style>
</head>

<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>مصنع الألبان</h1>
        <h2>تقرير {{ $period_label }}</h2>
        <p>من {{ $date_from->format('d/m/Y') }} إلى {{ $date_to->format('d/m/Y') }}</p>
        <p>تاريخ الطباعة: {{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-row">
        <div class="summary-box">
            <div class="summary-value" style="color:#3a7fd4;">{{ number_format($totalMilkLiters, 1) }} لتر</div>
            <div class="summary-label">إجمالي الحليب المجموع</div>
        </div>
        <div class="summary-box">
            <div class="summary-value" style="color:#7b1fa2;">{{ number_format($totalDistributionValue, 0) }}</div>
            <div class="summary-label">إجمالي المبيعات (جنيه)</div>
        </div>
        <div class="summary-box">
            <div class="summary-value" style="color:#e53935;">{{ number_format($totalExpenses, 0) }}</div>
            <div class="summary-label">إجمالي المصروفات (جنيه)</div>
        </div>
        <div class="summary-box">
            <div class="summary-value" style="color:#e53935;">{{ number_format($totalOutstandingDebts, 0) }}</div>
            <div class="summary-label">الديون المتبقية (جنيه)</div>
        </div>
    </div>

    {{-- MILK COLLECTIONS --}}
    <div class="section">
        <div class="section-title">جمع الحليب ({{ $milkCollections->count() }} عملية)</div>
        @if($milkCollections->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>المزارع</th>
                    <th>السائق</th>
                    <th>رقم السيارة</th>
                    <th>الكمية (لتر)</th>
                    <th>سعر اللتر</th>
                    <th>الإجمالي</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milkCollections as $milk)
                <tr>
                    <td>{{ $milk->farmer_name }}</td>
                    <td>{{ $milk->driver_name }}</td>
                    <td>{{ $milk->vehicle_number }}</td>
                    <td>{{ number_format($milk->quantity_liters, 1) }}</td>
                    <td>{{ number_format($milk->price_per_liter, 2) }}</td>
                    <td><strong>{{ number_format($milk->total_amount, 2) }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($milk->collection_date)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="3"><strong>الإجمالي</strong></td>
                    <td><strong>{{ number_format($totalMilkLiters, 1) }}</strong></td>
                    <td>-</td>
                    <td><strong>{{ number_format($totalMilkValue, 2) }}</strong></td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="no-data">لا توجد بيانات في هذه الفترة</p>
        @endif
    </div>

    {{-- PRODUCTION --}}
    <div class="section">
        @php $ptypes = \App\Models\Production::productTypes(); @endphp
        <div class="section-title">الإنتاج ({{ $productions->count() }} دفعة)</div>
        @if($productions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>اسم المنتج</th>
                    <th>النوع</th>
                    <th>الكمية</th>
                    <th>الوحدة</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productions as $prod)
                <tr>
                    <td>{{ $prod->product_name }}</td>
                    <td>{{ $ptypes[$prod->product_type] ?? $prod->product_type }}</td>
                    <td>{{ number_format($prod->quantity, 1) }}</td>
                    <td>{{ $prod->unit }}</td>
                    <td>{{ $prod->production_date->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="no-data">لا توجد بيانات</p>
        @endif
    </div>

    {{-- DISTRIBUTION --}}
    <div class="section">
        <div class="section-title">التوزيع ({{ $distributions->count() }} عملية)</div>
        @if($distributions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>المحل</th>
                    <th>السائق</th>
                    <th>المنتجات</th>
                    <th>الإجمالي</th>
                    <th>التاريخ</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distributions as $dist)
                <tr>
                    <td>{{ $dist->shop_name }}</td>
                    <td>{{ $dist->driver_name }}</td>
                    <td>{{ $dist->items->pluck('product_name')->join(' - ') }}</td>
                    <td><strong>{{ number_format($dist->total_value, 2) }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($dist->delivery_date)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="3"><strong>إجمالي المبيعات</strong></td>
                    <td><strong>{{ number_format($totalDistributionValue, 2) }}</strong></td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="no-data">لا توجد بيانات</p>
        @endif
    </div>

    {{-- EXPENSES --}}
    <div class="section">
        @php $cats = \App\Models\Expense::categories(); @endphp
        <div class="section-title">المصروفات ({{ $expenses->count() }} بند)</div>
        @if($expenses->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>التصنيف</th>
                    <th>المبلغ</th>
                    <th>التاريخ</th>
                    <th>ملاحظات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $exp)
                <tr>
                    <td>{{ $cats[$exp->category] ?? $exp->category }}</td>
                    <td>{{ number_format($exp->amount, 2) }}</td>
                    <td>{{ $exp->expense_date->format('d/m/Y') }}</td>
                    <td>{{ $exp->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td><strong>الإجمالي</strong></td>
                    <td><strong>{{ number_format($totalExpenses, 2) }}</strong></td>
                    <td colspan="2">-</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="no-data">لا توجد مصروفات</p>
        @endif
    </div>

    {{-- NET BALANCE --}}
    <div class="net-balance">
        <div class="label">الرصيد الصافي للفترة (مبيعات + قيمة حليب - مصروفات)</div>
        <div class="value" style="color: {{ $netBalance >= 0 ? '#2565AE' : '#e53935' }};">
            {{ $netBalance >= 0 ? '+' : '' }}{{ number_format($netBalance, 2) }} جنيه
        </div>
    </div>

    <div class="footer">
        تم إنشاء هذا التقرير بواسطة نظام إدارة مصنع الألبان • {{ now()->format('d/m/Y H:i') }}
    </div>

</body>

</html>