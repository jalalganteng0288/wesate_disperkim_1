<?php

// Pastikan namespace ini benar
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\InfrastructureReport;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MapController extends Controller
{
    /**
     * Menampilkan halaman utama peta.
     */
    public function index()
    {
        return view('admin.peta.index');
    }

    /**
     * Menyediakan data lokasi sebagai JSON untuk peta.
     */
    public function locations(): JsonResponse
    {
        // Ambil data pengaduan yang memiliki koordinat
        $complaints = Pengaduan::whereNotNull('latitude')->whereNotNull('longitude')->get()->map(function ($item) {
            return [
                'type' => 'pengaduan',
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'title' => $item->judul,
                'status' => $item->status,
                'url' => route('admin.pengaduan.show', $item->id),
            ];
        });

        // Ambil data laporan infrastruktur yang memiliki koordinat
        $infrastructure = InfrastructureReport::whereNotNull('latitude')->whereNotNull('longitude')->get()->map(function ($item) {
            return [
                'type' => 'infrastruktur',
                'latitude' => $item->latitude,
                'longitude' => $item->longitude,
                'title' => 'Laporan ' . $item->type,
                'status' => $item->status,
                'url' => route('admin.infrastruktur.show', $item->id),
            ];
        });

        // Gabungkan kedua data menjadi satu
        $allLocations = $complaints->merge($infrastructure);

        return response()->json($allLocations);
    }
}