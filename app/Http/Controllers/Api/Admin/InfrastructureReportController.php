<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfrastructureReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class InfrastructureReportController extends Controller
{
    public function index()
    {
        $reports = InfrastructureReport::with('user')->latest()->paginate(10);
        return response()->json($reports);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['required', 'string', 'in:jalan,drainase,lampu'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
            'severity' => ['required', 'string', 'in:rendah,sedang,tinggi'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('infrastructure', 'public');
        }

        // INI BAGIAN PALING PENTING YANG DIPERBAIKI
        $report = InfrastructureReport::create([
            'user_id' => 1, // Memastikan user_id diisi
            'type' => $request->type,
            'description' => $request->description,
            'photo_url' => $photoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'severity' => $request->severity,
            'status' => 'Baru',
        ]);

        return response()->json(['message' => 'Infrastructure report created successfully', 'report' => $report->load('user')], 201);
    }

    public function show(InfrastructureReport $infrastructureReport)
    {
        return response()->json($infrastructureReport->load('user'));
    }

    public function update(Request $request, InfrastructureReport $infrastructureReport)
    {
        $validator = Validator::make($request->all(), [
            'type' => ['sometimes', 'required', 'string', 'in:jalan,drainase,lampu'],
            'description' => ['sometimes', 'required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'latitude' => ['sometimes', 'required', 'numeric'],
            'longitude' => ['sometimes', 'required', 'numeric'],
            'severity' => ['sometimes', 'required', 'string', 'in:rendah,sedang,tinggi'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $validatedData = $validator->validated();

        if ($request->hasFile('photo')) {
            if ($infrastructureReport->photo_url) {
                Storage::disk('public')->delete($infrastructureReport->photo_url);
            }
            $validatedData['photo_url'] = $request->file('photo')->store('infrastructure', 'public');
        }

        $infrastructureReport->update($validatedData);

        return response()->json(['message' => 'Infrastructure report updated successfully', 'report' => $infrastructureReport->load('user')]);
    }

    public function destroy(InfrastructureReport $infrastructureReport)
    {
        if ($infrastructureReport->photo_url) {
            Storage::disk('public')->delete($infrastructureReport->photo_url);
        }
        $infrastructureReport->delete();
        return response()->json(['message' => 'Infrastructure report deleted successfully']);
    }
}