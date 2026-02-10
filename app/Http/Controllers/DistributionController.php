<?php

namespace App\Http\Controllers;

use App\Models\Distribution;
use App\Models\DistributionItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class DistributionController extends Controller
{
    public function index()
    {
        $distributions = Distribution::with('items')->latest('delivery_date')->paginate(15);
        return view('distribution.index', compact('distributions'));
    }

    public function create()
    {
        return view('distribution.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'shop_name'      => 'required|string|max:255',
            'driver_name'    => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:50',
            'delivery_date'  => 'required|date',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|numeric|min:0.01',
            'items.*.unit'         => 'required|string',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ], [
            'shop_name.required'      => 'اسم المحل مطلوب',
            'driver_name.required'    => 'اسم السائق مطلوب',
            'vehicle_number.required' => 'رقم السيارة مطلوب',
            'delivery_date.required'  => 'تاريخ التوصيل مطلوب',
            'items.required'          => 'يجب إضافة منتج واحد على الأقل',
            'items.min'               => 'يجب إضافة منتج واحد على الأقل',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $totalValue = 0;
            $itemsData = [];
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalValue += $subtotal;
                $itemsData[] = [
                    'product_name' => $item['product_name'],
                    'quantity'     => $item['quantity'],
                    'unit'         => $item['unit'],
                    'unit_price'   => $item['unit_price'],
                    'subtotal'     => $subtotal,
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ];
            }

            $distribution = Distribution::create([
                'shop_name'      => $validated['shop_name'],
                'driver_name'    => $validated['driver_name'],
                'vehicle_number' => $validated['vehicle_number'],
                'total_value'    => $totalValue,
                'delivery_date'  => $validated['delivery_date'],
                'notes'          => $validated['notes'] ?? null,
                'user_id'        => auth()->id(),
            ]);

            $distribution->items()->insert(
                array_map(fn($item) => array_merge($item, ['distribution_id' => $distribution->id]), $itemsData)
            );
        });

        return redirect()->route('distribution.index')
            ->with('success', 'تم تسجيل التوزيع بنجاح');
    }

    public function show(Distribution $distribution)
    {
        $distribution->load('items');
        return view('distribution.show', compact('distribution'));
    }

    public function edit(Distribution $distribution)
    {
        $distribution->load('items');
        return view('distribution.edit', compact('distribution'));
    }

    public function update(Request $request, Distribution $distribution)
    {
        $validated = $request->validate([
            'shop_name'      => 'required|string|max:255',
            'driver_name'    => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:50',
            'delivery_date'  => 'required|date',
            'notes'          => 'nullable|string',
            'items'          => 'required|array|min:1',
            'items.*.product_name' => 'required|string',
            'items.*.quantity'     => 'required|numeric|min:0.01',
            'items.*.unit'         => 'required|string',
            'items.*.unit_price'   => 'required|numeric|min:0',
        ], [
            'shop_name.required'      => 'اسم المحل مطلوب',
            'driver_name.required'    => 'اسم السائق مطلوب',
            'vehicle_number.required' => 'رقم السيارة مطلوب',
            'delivery_date.required'  => 'تاريخ التوصيل مطلوب',
            'items.required'          => 'يجب إضافة منتج واحد على الأقل',
            'items.min'               => 'يجب إضافة منتج واحد على الأقل',
        ]);

        DB::transaction(function () use ($validated, $request, $distribution) {
            $totalValue = 0;
            $itemsData = [];
            foreach ($request->items as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $totalValue += $subtotal;
                $itemsData[] = [
                    'product_name'    => $item['product_name'],
                    'quantity'        => $item['quantity'],
                    'unit'            => $item['unit'],
                    'unit_price'      => $item['unit_price'],
                    'subtotal'        => $subtotal,
                    'distribution_id' => $distribution->id,
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }

            $distribution->update([
                'shop_name'      => $validated['shop_name'],
                'driver_name'    => $validated['driver_name'],
                'vehicle_number' => $validated['vehicle_number'],
                'total_value'    => $totalValue,
                'delivery_date'  => $validated['delivery_date'],
                'notes'          => $validated['notes'] ?? null,
            ]);

            // Replace all items
            $distribution->items()->delete();
            DistributionItem::insert($itemsData);
        });

        return redirect()->route('distribution.index')
            ->with('success', 'تم تعديل التوزيع بنجاح');
    }

    public function destroy(Distribution $distribution)
    {
        $distribution->delete();
        return redirect()->route('distribution.index')
            ->with('success', 'تم حذف السجل بنجاح');
    }
}
