<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pengaduan; // PENTING: Import model Pengaduan
use App\Models\User; 
use Illuminate\Support\Facades\DB; // <-- PENTING: Import DB Facade
use Carbon\Carbon;                // <-- PENTING: Import Carbon     // Import model lain jika dibutuhkan
// use App\Models\Berita;

class DashboardController extends Controller
{
    /**
     * Menampilkan halaman dashboard admin beserta data ringkasannya.
     */
    public function index()
    {
        // Ambil data-data yang dibutuhkan untuk KPI cards dan list
       /// === Data untuk KPI Cards (ini tidak berubah) ===
        $pengaduanBaruCount = Pengaduan::where('status', 'baru')->count();
        $pengaduanDikerjakanCount = Pengaduan::where('status', 'pengerjaan')->count();
        $pengaduanSelesaiCount = Pengaduan::where('status', 'selesai')->count();
        $totalPengaduanCount = Pengaduan::count();

        // ========================================================================
        // === Data untuk Grafik Tren Bulanan (BAGIAN YANG DIPERBAIKI) ===
        // ========================================================================
        $year = Carbon::now()->year;
        $complaintsData = Pengaduan::select(
                // UBAH DARI 'MONTH(created_at)' MENJADI 'strftime(...)' UNTUK SQLite
                DB::raw("strftime('%m', created_at) as month"), 
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();

        // Konversi key bulan dari '01', '02' menjadi 1, 2, dst. agar cocok dengan loop.
        $complaintsData = collect($complaintsData)->mapWithKeys(function ($count, $month) {
            return [(int)$month => $count];
        })->all();
        // ========================================================================


        // Menyiapkan array untuk chart (ini tidak berubah)
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('F');
            array_push($chartLabels, $monthName);
            array_push($chartData, $complaintsData[$i] ?? 0);
        }

        // Kirim semua data tersebut ke view (ini tidak berubah)
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