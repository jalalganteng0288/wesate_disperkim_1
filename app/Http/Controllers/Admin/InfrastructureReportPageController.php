<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InfrastructureReport;
use Illuminate\Http\Request;

class InfrastructureReportPageController extends Controller
{
    public function index()
    {
        $reports = InfrastructureReport::with('user')->latest()->paginate(10);
        return view('admin.infrastruktur.index', compact('reports'));
    }

    public function show(InfrastructureReport $infrastructureReport)
    {
        $infrastructureReport->load('user');

        // PERBAIKAN UTAMA ADA DI SINI:
        // Mengirim variabel dengan nama 'infrastructureReport' agar sama dengan parameter route
        return view('admin.infrastruktur.show', ['infrastructureReport' => $infrastructureReport]);
    }

    public function updateStatus(Request $request, InfrastructureReport $infrastructureReport)
    {
        $request->validate([
            'status' => 'required|in:Baru,Verifikasi,Penjadwalan,Pengerjaan,Selesai,Ditolak',
        ]);

        $infrastructureReport->update(['status' => $request->status]);

        return redirect()->route('admin.infrastruktur.show', $infrastructureReport->id)
                         ->with('success', 'Status laporan berhasil diperbarui.');
    }
}