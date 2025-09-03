<?php

// PASTIKAN NAMESPACE-NYA ADALAH "Admin"
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin.
     */
    public function index()
    {
        // Data untuk Kotak KPI (Key Performance Indicator)
        $pengaduanBaruCount = Pengaduan::where('status', 'baru')->count();
        $pengaduanDikerjakanCount = Pengaduan::where('status', 'pengerjaan')->count();
        $pengaduanSelesaiCount = Pengaduan::where('status', 'selesai')->count();
        $totalPengaduanCount = Pengaduan::count();

        // Data untuk Grafik Tren Bulanan (INI YANG DIPERBAIKI UNTUK MYSQL)
        $year = Carbon::now()->year;
        $complaintsData = Pengaduan::select(
                // Menggunakan FUNGSI MYSQL: MONTH(created_at)
                DB::raw("MONTH(created_at) as month"), 
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        // Menyiapkan data untuk chart
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            array_push($chartLabels, $monthName);
            array_push($chartData, $complaintsData[$i] ?? 0);
        }

        // Kirim semua data ke view 'admin.dashboard'
        return view('admin.dashboard', [
            'pengaduanBaruCount' => $pengaduanBaruCount,
            'pengaduanDikerjakanCount' => $pengaduanDikerjakanCount,
            'pengaduanSelesaiCount' => $pengaduanSelesaiCount,
            'totalPengaduanCount' => $totalPengaduanCount,
            'pengaduanTerakhir' => Pengaduan::with('user')->latest()->take(5)->get(),
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
        ]);
    }
}