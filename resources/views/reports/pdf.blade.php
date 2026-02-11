<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Report</title>
    <style>
        @font-face {
            font-family: 'ArabicFont';
            src: url('{{ public_path("fonts/DejaVuSans.ttf") }}') format('truetype');
            font-weight: normal;
        }
        @font-face {
            font-family: 'ArabicFont';
            src: url('{{ public_path("fonts/DejaVuSans-Bold.ttf") }}') format('truetype');
            font-weight: bold;
        }
        * {
            font-family: 'ArabicFont', 'DejaVu Sans', sans-serif;
            /* No direction:rtl — we handle direction manually via reshaping + reversal */
        }
        body {
            font-size: 12px;
            color: #333;
            margin: 0;
            padding: 20px;
            background: white;
        }
        .header {
            text-align: center;
            border-bottom: 3px solid #2565AE;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 { font-size: 22px; color: #2565AE; margin: 0 0 5px 0; font-weight: bold; }
        .header h2 { font-size: 16px; color: #555; margin: 0 0 5px 0; }
        .header p  { font-size: 11px; color: #777; margin: 0; }

        .summary-grid { display: table; width: 100%; margin-bottom: 20px; }
        .summary-cell { display: table-cell; width: 25%; padding: 6px; text-align: center; }
        .summary-box  { border: 1px solid #ddd; border-radius: 6px; padding: 10px 6px; background: #f9f9f9; }
        .summary-value { font-size: 15px; font-weight: bold; margin-bottom: 4px; }
        .summary-label { font-size: 9px; color: #777; }

        .section { margin-bottom: 20px; }
        .section-title {
            background: #2565AE;
            color: white;
            padding: 8px 12px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 0;
            text-align: left; /* shaped Arabic reads LTR after reshaping */
        }

        table { width: 100%; border-collapse: collapse; font-size: 11px; }
        th {
            background: #e8f0fa;
            padding: 7px 8px;
            border: 1px solid #ccc;
            font-weight: bold;
            text-align: left; /* reshaped Arabic */
        }
        td { padding: 6px 8px; border: 1px solid #ddd; text-align: left; }
        tr:nth-child(even) td { background: #f9f9f9; }
        .tfoot-row td { background: #e8f0fa; font-weight: bold; }

        .net-balance {
            margin-top: 20px;
            padding: 15px;
            border: 2px solid #2565AE;
            border-radius: 8px;
            background: #e8f0fa;
            text-align: center;
        }
        .net-balance .label { font-size: 13px; color: #555; margin-bottom: 6px; }
        .net-balance .value { font-size: 22px; font-weight: bold; }

        .footer {
            margin-top: 30px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
            font-size: 10px;
            color: #999;
            text-align: center;
        }
        .no-data { padding: 10px; color: #999; }
    </style>
</head>
<body>

    {{-- HEADER --}}
    <div class="header">
        <h1>{{ $label_factory }}</h1>
        <h2>{{ $period_label }}</h2>
        <p>{{ $date_from->format('d/m/Y') }} - {{ $date_to->format('d/m/Y') }}</p>
        <p>{{ now()->format('d/m/Y H:i') }}</p>
    </div>

    {{-- SUMMARY CARDS --}}
    <div class="summary-grid">
        <div class="summary-cell">
            <div class="summary-box">
                <div class="summary-value" style="color:#3a7fd4;">{{ number_format($totalMilkLiters,1) }}</div>
                <div class="summary-label">{{ $label_milk_total }}</div>
            </div>
        </div>
        <div class="summary-cell">
            <div class="summary-box">
                <div class="summary-value" style="color:#7b1fa2;">{{ number_format($totalDistributionValue,0) }}</div>
                <div class="summary-label">{{ $label_sales_total }}</div>
            </div>
        </div>
        <div class="summary-cell">
            <div class="summary-box">
                <div class="summary-value" style="color:#e53935;">{{ number_format($totalExpenses,0) }}</div>
                <div class="summary-label">{{ $label_exp_total }}</div>
            </div>
        </div>
        <div class="summary-cell">
            <div class="summary-box">
                <div class="summary-value" style="color:#e53935;">{{ number_format($totalOutstandingDebts,0) }}</div>
                <div class="summary-label">{{ $label_debts_total }}</div>
            </div>
        </div>
    </div>

    {{-- MILK COLLECTIONS --}}
    <div class="section">
        <div class="section-title">{{ $label_milk }} ({{ $milkCollections->count() }})</div>
        @if($milkCollections->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>{{ $label_farmer }}</th>
                    <th>{{ $label_driver }}</th>
                    <th>{{ $label_vehicle }}</th>
                    <th>{{ $label_qty }}</th>
                    <th>{{ $label_price }}</th>
                    <th>{{ $label_subtotal }}</th>
                    <th>{{ $label_date }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($milkCollections as $milk)
                <tr>
                    <td>{{ $milk->farmer_name }}</td>
                    <td>{{ $milk->driver_name }}</td>
                    <td>{{ $milk->vehicle_number }}</td>
                    <td>{{ number_format($milk->quantity_liters,1) }}</td>
                    <td>{{ number_format($milk->price_per_liter,2) }}</td>
                    <td><strong>{{ number_format($milk->total_amount,2) }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($milk->collection_date)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="3"><strong>{{ $label_total }}</strong></td>
                    <td><strong>{{ number_format($totalMilkLiters,1) }}</strong></td>
                    <td>-</td>
                    <td><strong>{{ number_format($totalMilkValue,2) }}</strong></td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="no-data">{{ $label_no_data }}</p>
        @endif
    </div>

    {{-- PRODUCTION --}}
    <div class="section">
        <div class="section-title">{{ $label_production }} ({{ $productions->count() }})</div>
        @if($productions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>{{ $label_product }}</th>
                    <th>{{ $label_type }}</th>
                    <th>{{ $label_qty }}</th>
                    <th>{{ $label_unit }}</th>
                    <th>{{ $label_date }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($productions as $prod)
                <tr>
                    <td>{{ $prod->product_name }}</td>
                    <td>{{ $prod->product_type_label ?? '' }}</td>
                    <td>{{ number_format($prod->quantity,1) }}</td>
                    <td>{{ $prod->unit }}</td>
                    <td>{{ $prod->production_date->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p class="no-data">{{ $label_no_data }}</p>
        @endif
    </div>

    {{-- DISTRIBUTION --}}
    <div class="section">
        <div class="section-title">{{ $label_distribution }} ({{ $distributions->count() }})</div>
        @if($distributions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>{{ $label_shop }}</th>
                    <th>{{ $label_driver }}</th>
                    <th>{{ $label_products }}</th>
                    <th>{{ $label_subtotal }}</th>
                    <th>{{ $label_date }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($distributions as $dist)
                <tr>
                    <td>{{ $dist->shop_name }}</td>
                    <td>{{ $dist->driver_name }}</td>
                    <td>{{ $dist->items->pluck('product_name')->join(' / ') }}</td>
                    <td><strong>{{ number_format($dist->total_value,2) }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($dist->delivery_date)->format('d/m/Y') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td colspan="3"><strong>{{ $label_total_sales }}</strong></td>
                    <td><strong>{{ number_format($totalDistributionValue,2) }}</strong></td>
                    <td>-</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="no-data">{{ $label_no_data }}</p>
        @endif
    </div>

    {{-- EXPENSES --}}
    <div class="section">
        <div class="section-title">{{ $label_expenses }} ({{ $expenses->count() }})</div>
        @if($expenses->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>{{ $label_category }}</th>
                    <th>{{ $label_amount }}</th>
                    <th>{{ $label_date }}</th>
                    <th>{{ $label_notes }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($expenses as $exp)
                <tr>
                    <td>{{ $exp->category_label ?? '' }}</td>
                    <td>{{ number_format($exp->amount,2) }}</td>
                    <td>{{ $exp->expense_date->format('d/m/Y') }}</td>
                    <td>{{ $exp->notes ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="tfoot-row">
                    <td><strong>{{ $label_total }}</strong></td>
                    <td><strong>{{ number_format($totalExpenses,2) }}</strong></td>
                    <td colspan="2">-</td>
                </tr>
            </tfoot>
        </table>
        @else
        <p class="no-data">{{ $label_no_data }}</p>
        @endif
    </div>

    {{-- NET BALANCE --}}
    <div class="net-balance">
        <div class="label">{{ $label_net_balance }} ({{ $label_net_formula }})</div>
        <div class="value" style="color: {{ $netBalance >= 0 ? '#2565AE' : '#e53935' }};">
            {{ $netBalance >= 0 ? '+' : '' }}{{ number_format($netBalance,2) }} {{ $label_gineh }}
        </div>
    </div>

    <div class="footer">
        {{ $label_footer }} • {{ now()->format('d/m/Y H:i') }}
    </div>

</body>
</html>
