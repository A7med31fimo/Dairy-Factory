<?php

namespace App\Http\Controllers;

use App\Models\Production;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductionController extends Controller
{
    public function index()
    {
        $productions = Production::latest('production_date')->paginate(15);
        $productTypes = Production::productTypes();
        return view('production.index', compact('productions', 'productTypes'));
    }

    public function create()
    {
        $productTypes = Production::productTypes();
        return view('production.create', compact('productTypes'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_type'    => 'required|string',
            'product_name'    => 'required|string|max:255',
            'quantity'        => 'required|numeric|min:0.01',
            'unit'            => 'required|string|max:20',
            'production_date' => 'required|date',
            'notes'           => 'nullable|string',
        ], [
            'product_type.required'    => 'نوع المنتج مطلوب',
            'product_name.required'    => 'اسم المنتج مطلوب',
            'quantity.required'        => 'الكمية مطلوبة',
            'unit.required'            => 'الوحدة مطلوبة',
            'production_date.required' => 'تاريخ الإنتاج مطلوب',
        ]);

        $validated['user_id'] = auth()->id();

        Production::create($validated);

        return redirect()->route('production.index')
            ->with('success', 'تم تسجيل الإنتاج بنجاح');
    }

    public function show(Production $production)
    {
        $productTypes = Production::productTypes();
        return view('production.show', compact('production', 'productTypes'));
    }

    public function edit(Production $production)
    {
        $productTypes = Production::productTypes();
        return view('production.edit', compact('production', 'productTypes'));
    }

    public function update(Request $request, Production $production)
    {
        $validated = $request->validate([
            'product_type'    => 'required|string',
            'product_name'    => 'required|string|max:255',
            'quantity'        => 'required|numeric|min:0.01',
            'unit'            => 'required|string|max:20',
            'production_date' => 'required|date',
            'notes'           => 'nullable|string',
        ]);

        $production->update($validated);

        return redirect()->route('production.index')
            ->with('success', 'تم تحديث سجل الإنتاج بنجاح');
    }

    public function destroy(Production $production)
    {
        $production->delete();
        return redirect()->route('production.index')
            ->with('success', 'تم حذف السجل بنجاح');
    }
}
