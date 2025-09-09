<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use App\Models\HousingUnit; // <-- PERBAIKAN UTAMA: Tambahkan baris ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class PengaduanPageController extends Controller
{
    /**
     * Menampilkan daftar semua pengaduan.
     */
    public function index(Request $request)
    {
        // Ambil query builder untuk model Pengaduan
        $query = Pengaduan::with(['user', 'housingUnit'])->latest();

        // --- LOGIKA FILTER ---
        // 1. Filter berdasarkan kata kunci (pencarian)
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%');
        }

        // 2. Filter berdasarkan status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // 3. Filter berdasarkan rentang tanggal
        if ($request->has('start_date') && $request->start_date != '') {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->has('end_date') && $request->end_date != '') {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
        // --- AKHIR LOGIKA FILTER ---

        // Eksekusi query dengan paginasi
        $pengaduans = $query->paginate(10)->withQueryString(); // withQueryString() agar filter tetap aktif saat pindah halaman

        return view('admin.pengaduan.index', compact('pengaduans'));
    }

    /**
     * Menampilkan form untuk membuat pengaduan baru.
     */
    public function create()
    {
        // Mengambil semua data perumahan untuk ditampilkan di dropdown
        $housingUnits = HousingUnit::orderBy('name')->get();
        return view('admin.pengaduan.create', compact('housingUnits'));
    }

    /**
     * Menyimpan pengaduan baru ke database.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'housing_unit_id' => 'nullable|exists:housing_units,id', // Validasi housing_unit_id
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'baru';

        Pengaduan::create($validatedData);

        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan detail pengaduan.
     */
    public function show(Pengaduan $pengaduan)
    {
        // Memuat relasi user dan housingUnit agar datanya tersedia di view
        $pengaduan->load(['user', 'housingUnit']);
        return view('admin.pengaduan.show', compact('pengaduan'));
    }

    /**
     * Menampilkan form untuk mengedit pengaduan.
     */
    public function edit(Pengaduan $pengaduan)
    {
        $statuses = ['baru', 'pengerjaan', 'selesai', 'ditolak'];
        $housingUnits = HousingUnit::orderBy('name')->get();
        return view('admin.pengaduan.edit', compact('pengaduan', 'statuses', 'housingUnits'));
    }

    /**
     * Memperbarui data pengaduan di database.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'housing_unit_id' => 'nullable|exists:housing_units,id', // Validasi housing_unit_id
        ]);

        $pengaduan->update($validatedData);

        return redirect()->route('admin.pengaduan.show', $pengaduan->id)->with('success', 'Pengaduan berhasil diperbarui.');
    }

    /**
     * Menghapus pengaduan dari database.
     */
    public function destroy(Pengaduan $pengaduan)
    {
        $pengaduan->delete();
        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.');
    }

    /**
     * Memperbarui status pengaduan.
     */
    public function updateStatus(Request $request, Pengaduan $pengaduan)
    {
        $request->validate([
            'status' => 'required|in:baru,pengerjaan,selesai,ditolak',
        ]);

        $pengaduan->update(['status' => $request->status]);

        // Notifikasi bisa ditambahkan di sini nanti

        return redirect()->route('admin.pengaduan.show', $pengaduan->id)
            ->with('success', 'Status pengaduan berhasil diperbarui.');
    }
}
