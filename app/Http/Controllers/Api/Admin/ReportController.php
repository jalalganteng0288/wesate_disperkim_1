<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Export data pengaduan ke dalam format CSV.
     */
    public function exportComplaints()
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="laporan-pengaduan-'.date('Y-m-d').'.csv"',
        ];

        // Ambil semua data pengaduan beserta relasi user
        $complaints = Complaint::with('user')->get();

        $callback = function() use ($complaints) {
            $file = fopen('php://output', 'w');

            // 1. Tulis Header Kolom
            $columns = ['ID', 'Judul', 'Deskripsi', 'Pelapor', 'Status', 'Tanggal Dibuat'];
            fputcsv($file, $columns);

            // 2. Tulis setiap baris data
            foreach ($complaints as $complaint) {
                fputcsv($file, [
                    $complaint->id,
                    $complaint->title,
                    $complaint->description,
                    $complaint->user->name ?? 'N/A', // Handle jika user sudah dihapus
                    $complaint->status,
                    $complaint->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        // Mengirim respons sebagai file download
        return new StreamedResponse($callback, 200, $headers);
    }
}