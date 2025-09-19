<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kecamatan; // <-- Panggil Model baru
use Illuminate\Http\Request;

class KecamatanController extends Controller
{
    public function index()
    {
        // 1. Ambil Data KPI Global
        $totalKecamatan = Kecamatan::count();
        $totalPopulasi = Kecamatan::sum('populasi');
        $totalRumah = Kecamatan::sum('total_rumah');
        $totalRutilahu = Kecamatan::sum('total_rutilahu');
        
        // Hitung Rata-rata Rutilahu ( (Total Rutilahu / Total Rumah) * 100 )
        $avgRutilahuPercentage = ($totalRumah > 0) ? ($totalRutilahu / $totalRumah) * 100 : 0;

        // 2. Ambil Daftar Kecamatan (dengan data relasi)
        // Kita hitung jumlah pengaduan, dan pengaduan yang selesai
        $kecamatans = Kecamatan::withCount([
            'pengaduans',
            'pengaduans as pengaduan_selesai_count' => function ($query) {
                $query->where('status', 'selesai');
            }
        ])
        ->orderBy('name', 'asc') // Urutkan A-Z
        ->get();


        return view('admin.kecamatan.index', compact(
            'totalKecamatan',
            'totalPopulasi',
            'totalRumah',
            'avgRutilahuPercentage',
            'kecamatans'
        ));
    }
}