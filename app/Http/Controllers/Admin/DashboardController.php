<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\User;
use App\Models\HousingUnit;
use App\Models\InfrastructureReport;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // === Data untuk Kartu KPI LAMA ===
        $pengaduanBaruCount = Pengaduan::where('status', 'baru')->count();
        $pengaduanDikerjakanCount = Pengaduan::where('status', 'pengerjaan')->count();
        $pengaduanSelesaiCount = Pengaduan::where('status', 'selesai')->count();
        $totalPengaduanCount = Pengaduan::count();
        $pengaduanTerakhir = Pengaduan::with('user')->latest()->take(5)->get();

        // === Data untuk Kartu KPI BARU (MODERN) ===
        $proyekInfrastrukturCount = InfrastructureReport::count();
        $totalPerumahanCount = HousingUnit::count();
        $totalPenggunaCount = User::count();

        // ========================================================================
        // === PERBAIKAN UTAMA ADA DI SINI ===
        // ========================================================================
        $year = Carbon::now()->year;
        $complaintsData = Pengaduan::select(
                // Menggunakan FUNGSI MYSQL: MONTH(created_at) bukan strftime
                DB::raw("MONTH(created_at) as month"),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();
        // ========================================================================

        // Menyiapkan array untuk chart (tidak berubah)
        $chartLabels = [];
        $chartData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('M');
            $chartLabels[] = $monthName;
            $chartData[] = $complaintsData[$i] ?? 0;
        }

        // === Data untuk Aktivitas Terbaru ===
        $aktivitasTerbaru = AuditLog::with('actor')->latest()->take(5)->get();

        // Kirim semua data ke view
        return view('admin.dashboard', [
            // Data lama
            'pengaduanBaruCount' => $pengaduanBaruCount,
            'pengaduanDikerjakanCount' => $pengaduanDikerjakanCount,
            'pengaduanSelesaiCount' => $pengaduanSelesaiCount,
            'totalPengaduanCount' => $totalPengaduanCount,
            'pengaduanTerakhir' => $pengaduanTerakhir,
            // Data baru
            'proyekInfrastrukturCount' => $proyekInfrastrukturCount,
            'totalPerumahanCount' => $totalPerumahanCount,
            'totalPenggunaCount' => $totalPenggunaCount,
            'chartLabels' => $chartLabels,
            'chartData' => $chartData,
            'aktivitasTerbaru' => $aktivitasTerbaru,
        ]);
    }
}