<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EthosSection;
use App\Models\EthosValue;
use Illuminate\Http\Request;

class EthosController extends Controller
{
    // Sections
    public function sections()
    {
        $sections = EthosSection::ordered()->get();
        return view('admin.brand.ethos.sections.index', compact('sections'));
    }

    public function createSection()
    {
        return view('admin.brand.ethos.sections.form');
    }

    public function storeSection(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url|max:500',
            'image_position' => 'required|in:left,right,background,full',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        EthosSection::create($validated);

        return redirect()->route('admin.brand.ethos.sections')->with('success', 'Section created successfully.');
    }

    public function editSection(EthosSection $section)
    {
        return view('admin.brand.ethos.sections.form', compact('section'));
    }

    public function updateSection(Request $request, EthosSection $section)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image_url' => 'nullable|url|max:500',
            'image_position' => 'required|in:left,right,background,full',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $section->update($validated);

        return redirect()->route('admin.brand.ethos.sections')->with('success', 'Section updated successfully.');
    }

    public function deleteSection(EthosSection $section)
    {
        $section->delete();
        return redirect()->route('admin.brand.ethos.sections')->with('success', 'Section deleted successfully.');
    }

    // Values
    public function values()
    {
        $values = EthosValue::ordered()->get();
        return view('admin.brand.ethos.values.index', compact('values'));
    }

    public function createValue()
    {
        return view('admin.brand.ethos.values.form');
    }

    public function storeValue(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image_url' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        EthosValue::create($validated);

        return redirect()->route('admin.brand.ethos.values')->with('success', 'Value created successfully.');
    }

    public function editValue(EthosValue $value)
    {
        return view('admin.brand.ethos.values.form', compact('value'));
    }

    public function updateValue(Request $request, EthosValue $value)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image_url' => 'nullable|url|max:500',
            'sort_order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $value->update($validated);

        return redirect()->route('admin.brand.ethos.values')->with('success', 'Value updated successfully.');
    }

    public function deleteValue(EthosValue $value)
    {
        $value->delete();
        return redirect()->route('admin.brand.ethos.values')->with('success', 'Value deleted successfully.');
    }
}
