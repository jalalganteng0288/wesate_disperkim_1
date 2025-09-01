<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\InfrastructureReport;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // <-- Penting untuk query yang kompleks

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Kartu KPI (Key Performance Indicator)
        $kpi = [
            'complaints' => [
                'new' => Complaint::where('status', 'Baru Masuk')->count(),
                'in_progress' => Complaint::where('status', 'Dalam Pengerjaan')->count(),
                'completed' => Complaint::where('status', 'Selesai Dikerjakan')->count(),
                'total' => Complaint::count(),
            ],
            'infrastructure_reports' => InfrastructureReport::count(),
            'published_news' => News::where('is_published', true)->count(),
        ];

        // 2. Grafik Tren Pengaduan (Contoh: 7 hari terakhir)
        $complaintTrend = Complaint::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // 3. Daftar Item Terbaru
        $latest = [
            'complaints' => Complaint::with('user:id,name')->latest()->take(5)->get(),
            'infrastructure_reports' => InfrastructureReport::with('user:id,name')->latest()->take(5)->get(),
            'news' => News::with('author:id,name')->where('is_published', true)->latest()->take(3)->get(),
        ];

        // Gabungkan semua data menjadi satu respons
        $dashboardData = [
            'kpi' => $kpi,
            'complaint_trend' => $complaintTrend,
            'latest_items' => $latest,
        ];

        return response()->json($dashboardData);
    }
}