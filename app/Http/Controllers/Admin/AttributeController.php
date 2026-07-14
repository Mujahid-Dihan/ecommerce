<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $attributes = Attribute::with('values')->latest()->paginate(15);
        
        return Inertia::render('Admin/Attributes/Index', [
            'attributes' => $attributes
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name',
            'values' => 'nullable|string', // comma separated values
        ]);

        $attribute = Attribute::create([
            'name' => $validated['name']
        ]);

        if (!empty($validated['values'])) {
            $valuesArray = array_map('trim', explode(',', $validated['values']));
            foreach (array_unique($valuesArray) as $value) {
                if (!empty($value)) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }
        }

        return redirect()->back()->with('success', 'Attribute created successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Attribute $attribute)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:attributes,name,' . $attribute->id,
            'values' => 'nullable|string', // comma separated values
        ]);

        $attribute->update([
            'name' => $validated['name']
        ]);

        if (isset($validated['values'])) {
            $valuesArray = array_unique(array_filter(array_map('trim', explode(',', $validated['values']))));
            
            $attribute->values()->whereNotIn('value', $valuesArray)->delete();

            $existingValues = $attribute->values()->pluck('value')->toArray();
            foreach ($valuesArray as $value) {
                if (!in_array($value, $existingValues)) {
                    AttributeValue::create([
                        'attribute_id' => $attribute->id,
                        'value' => $value
                    ]);
                }
            }
        } else {
            $attribute->values()->delete();
        }

        return redirect()->back()->with('success', 'Attribute updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Attribute $attribute)
    {
        $attribute->delete();
        return redirect()->back()->with('success', 'Attribute deleted successfully.');
    }
}
