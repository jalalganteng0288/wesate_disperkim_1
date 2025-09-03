<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pengaduan;
use Barryvdh\DomPDF\Facade\Pdf; // Import library PDF
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    /**
     * Membuat dan mengunduh laporan pengaduan dalam format PDF.
     */
    public function exportComplaintsPDF()
    {
        $complaints = Pengaduan::with(['user', 'housingUnit'])->latest()->get();
        $data = [
            'title' => 'Laporan Data Pengaduan',
            'date' => date('d/m/Y'),
            'complaints' => $complaints
        ];

        // Muat view Blade untuk PDF dan kirimkan data
        $pdf = PDF::loadView('admin.reports.complaints_pdf', $data);

        // Atur ukuran kertas dan orientasi
        $pdf->setPaper('a4', 'landscape');

        // Unduh file PDF
        return $pdf->download('laporan-pengaduan-' . date('Y-m-d') . '.pdf');
    }

    /**
     * Membuat dan mengunduh laporan pengaduan dalam format CSV.
     */
    public function exportComplaintsCSV()
    {
        $fileName = 'laporan-pengaduan-' . date('Y-m-d') . '.csv';
        $complaints = Pengaduan::with(['user', 'housingUnit'])->latest()->get();

        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Judul', 'Pelapor', 'Lokasi Perumahan', 'Status', 'Tanggal Lapor'];

        $callback = function() use($complaints, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($complaints as $complaint) {
                $row['ID'] = $complaint->id;
                $row['Judul'] = $complaint->judul;
                $row['Pelapor'] = $complaint->user->name ?? 'N/A';
                $row['Lokasi Perumahan'] = $complaint->housingUnit->name ?? 'N/A';
                $row['Status'] = $complaint->status;
                $row['Tanggal Lapor'] = $complaint->created_at->format('Y-m-d H:i:s');

                fputcsv($file, [
                    $row['ID'], $row['Judul'], $row['Pelapor'], 
                    $row['Lokasi Perumahan'], $row['Status'], $row['Tanggal Lapor']
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}