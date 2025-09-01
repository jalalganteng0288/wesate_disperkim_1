<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\HousingUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HousingUnitController extends Controller
{
    public function index()
    {
        return HousingUnit::latest()->paginate(10);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255', 'unique:housing_units,name'],
            'address' => ['required', 'string'],
            'developer_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'unit_type' => ['required', 'string', 'max:100'],
            'total_units' => ['required', 'integer', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $housingUnit = HousingUnit::create($validator->validated());

        return response()->json(['message' => 'Housing unit created successfully', 'housing_unit' => $housingUnit], 201);
    }

    public function show(HousingUnit $housingUnit)
    {
        return response()->json($housingUnit);
    }

    public function update(Request $request, HousingUnit $housingUnit)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255', 'unique:housing_units,name,' . $housingUnit->id],
            'address' => ['sometimes', 'required', 'string'],
            'developer_name' => ['sometimes', 'required', 'string', 'max:255'],
            'contact_person' => ['sometimes', 'required', 'string', 'max:255'],
            'unit_type' => ['sometimes', 'required', 'string', 'max:100'],
            'total_units' => ['sometimes', 'required', 'integer', 'min:0'],
            'latitude' => ['nullable', 'numeric'],
            'longitude' => ['nullable', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $housingUnit->update($validator->validated());

        return response()->json(['message' => 'Housing unit updated successfully', 'housing_unit' => $housingUnit]);
    }

    public function destroy(HousingUnit $housingUnit)
    {
        $housingUnit->delete();
        return response()->json(['message' => 'Housing unit deleted successfully']);
    }
}