<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingUnit;
use Illuminate\Http\Request;

class HousingUnitPageController extends Controller
{
    public function index()
    {
        $housingUnits = HousingUnit::latest()->paginate(10);
        return view('admin.perumahan.index', compact('housingUnits'));
    }

    public function create()
    {
        return view('admin.perumahan.create');
    }

    public function store(Request $request)
    {
        // TAMBAHKAN VALIDASI UNTUK 'unit_type'
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'developer_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'unit_type' => 'required|string|max:100', // <-- Tambahkan ini
            'total_units' => 'required|integer|min:0',
        ]);

        HousingUnit::create($request->all());

        return redirect()->route('admin.perumahan.index')
                        ->with('success', 'Data perumahan berhasil ditambahkan.');
    }

    public function edit(HousingUnit $perumahan)
    {
        return view('admin.perumahan.edit', compact('perumahan'));
    }

    public function update(Request $request, HousingUnit $perumahan)
    {
        // TAMBAHKAN VALIDASI UNTUK 'unit_type'
        $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string',
            'developer_name' => 'required|string|max:255',
            'contact_person' => 'required|string|max:255',
            'unit_type' => 'required|string|max:100', // <-- Tambahkan ini
            'total_units' => 'required|integer|min:0',
        ]);

        $perumahan->update($request->all());

        return redirect()->route('admin.perumahan.index')
                        ->with('success', 'Data perumahan berhasil diperbarui.');
    }

    public function destroy(HousingUnit $perumahan)
    {
        $perumahan->delete();
        return redirect()->route('admin.perumahan.index')
                        ->with('success', 'Data perumahan berhasil dihapus.');
    }
}