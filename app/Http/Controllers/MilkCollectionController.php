<?php

namespace App\Http\Controllers;

use App\Models\MilkCollection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MilkCollectionController extends Controller
{
    public function index()
    {
        $collections = MilkCollection::latest('collection_date')->paginate(15);
        return view('milk.index', compact('collections'));
    }

    public function create()
    {
        return view('milk.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'farmer_name'    => 'required|string|max:255',
            'driver_name'    => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:50',
            'quantity_liters'=> 'required|numeric|min:0.01',
            'price_per_liter'=> 'required|numeric|min:0.01',
            'collection_date'=> 'required|date',
            'notes'          => 'nullable|string',
        ], [
            'farmer_name.required'    => 'اسم المزارع مطلوب',
            'driver_name.required'    => 'اسم السائق مطلوب',
            'vehicle_number.required' => 'رقم السيارة مطلوب',
            'quantity_liters.required'=> 'الكمية مطلوبة',
            'quantity_liters.min'     => 'الكمية يجب أن تكون أكبر من صفر',
            'price_per_liter.required'=> 'سعر الليتر مطلوب',
            'collection_date.required'=> 'التاريخ مطلوب',
        ]);

        $validated['total_amount'] = $validated['quantity_liters'] * $validated['price_per_liter'];
        $validated['user_id'] = auth()->id();

        MilkCollection::create($validated);

        return redirect()->route('milk.index')
            ->with('success', 'تم تسجيل جمع الحليب بنجاح');
    }

    public function show(MilkCollection $milkCollection)
    {
        return view('milk.show', [
            'milkCollection' => $milkCollection
        ]);
    }


    public function edit(MilkCollection $milk)
    {
        return view('milk.edit',  [
            'milkCollection' => $milk
        ]);
    }

    public function update(Request $request,  $id)
    {
        $milkCollection = MilkCollection::findOrFail($id);
     
        $validated = $request->validate([
            'farmer_name'    => 'required|string|max:255',
            'driver_name'    => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:50',
            'quantity_liters'=> 'required|numeric|min:0.01',
            'price_per_liter'=> 'required|numeric|min:0.01',
            'collection_date'=> 'required|date',
            'notes'          => 'nullable|string',
        ]);

        $validated['total_amount'] = $validated['quantity_liters'] * $validated['price_per_liter'];
        $milkCollection->update($validated);

        return redirect()->route('milk.index')
            ->with('success', 'تم تحديث سجل الجمع بنجاح');
    }

    public function destroy($id)
    {
        $milkCollection = MilkCollection::findOrFail($id);
        $milkCollection->delete();
        return redirect()->route('milk.index')
            ->with('success', 'تم حذف السجل بنجاح');
    }
}
