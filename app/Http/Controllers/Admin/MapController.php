<?php

namespace App\Http.Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan; // <-- Kita butuh ini
use App\Models\InfrastructureReport;
use App\Models\Kecamatan; // <-- Kita butuh ini
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB; // <-- Kita butuh ini
use Carbon\Carbon; // <-- Kita butuh ini

class MapController extends Controller
{
    /**
     * Menampilkan halaman utama statistik pengaduan.
     * (Ini adalah FUNGSI BARU untuk mengambil data statistik)
     */
    public function index()
    {
        // 1. Data untuk Tab "Trend Bulanan"
        $year = Carbon::now()->year;
        $complaintsData = Pengaduan::select(
                DB::raw("MONTH(created_at) as month"),
                DB::raw('COUNT(*) as count')
            )
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('count', 'month')
            ->all();
        
        $chartBulananLabels = [];
        $chartBulananData = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthName = Carbon::create()->month($i)->format('M');
            $chartBulananLabels[] = $monthName;
            $chartBulananData[] = $complaintsData[$i] ?? 0;
        }
        $dataBulanan = [
            'labels' => $chartBulananLabels,
            'data' => $chartBulananData,
        ];


        // 2. Data untuk Tab "Per Kecamatan"
        $pengaduanKecamatan = Pengaduan::query()
            ->select('kecamatan_id', DB::raw('count(*) as total'))
            ->groupBy('kecamatan_id')
            ->with('kecamatan:id,name') // Ambil relasi nama kecamatan
            ->get();
            
        $dataKecamatan = [
            // PERBAIKAN DI SINI: Tambahkan tanda tanya (?) setelah ->kecamatan
            'labels' => $pengaduanKecamatan->map(fn($item) => $item->kecamatan?->name ?? 'Tidak Diketahui'),
            'data' => $pengaduanKecamatan->map(fn($item) => $item->total),
        ];


        // 3. Data untuk Tab "Status"
        $pengaduanStatus = Pengaduan::query()
            ->select('status', DB::raw('count(*) as total'))
            ->groupBy('status')
            ->get();

        $dataStatus = [
            'labels' => $pengaduanStatus->map(fn($item) => ucfirst($item->status)),
            'data' => $pengaduanStatus->map(fn($item) => $item->total),
        ];

        // 4. Kirim semua data ke view baru
        return view('admin.peta.index', compact(
            'dataBulanan',
            'dataKecamatan',
            'dataStatus'
        ));
    }

    /**
     * Menyediakan data lokasi sebagai JSON untuk peta.
     * (Method ini sudah benar dari awal, kita biarkan)
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
