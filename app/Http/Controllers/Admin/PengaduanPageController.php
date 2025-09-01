<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule; // <-- TAMBAHKAN BARIS INI


class PengaduanPageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pengaduans = Pengaduan::with('user')->latest()->paginate(10);

        return view('admin.pengaduan.index', compact('pengaduans')); //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.pengaduan.create'); //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
        ]);

        // 2. Tambahkan data yang tidak ada di form
        $validatedData['user_id'] = Auth::id();
        $validatedData['status'] = 'baru';

        // 3. Simpan ke database
        Pengaduan::create($validatedData);

        // 4. Redirect dengan pesan sukses
        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan baru berhasil ditambahkan.'); //
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $pengaduan)
    {
        return view('admin.pengaduan.show', compact('pengaduan'));
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pengaduan $pengaduan)
    {
        $statuses = ['baru', 'verifikasi', 'pengerjaan', 'selesai']; // Daftar status yang valid
        return view('admin.pengaduan.edit', compact('pengaduan', 'statuses'));
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $pengaduan)
    {
        $statuses = ['baru', 'verifikasi', 'pengerjaan', 'selesai'];

        $validatedData = $request->validate([
            'judul' => 'required|string|max:255',
            'isi_laporan' => 'required|string',
            'status' => ['required', Rule::in($statuses)], // Pastikan status yang diinput valid
        ]);

        $pengaduan->update($validatedData);

        // Redirect ke halaman detail untuk melihat perubahan
        return redirect()->route('admin.pengaduan.show', $pengaduan->id)->with('success', 'Pengaduan berhasil diperbarui.');//
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $pengaduan)
    {
       $pengaduan->delete();

        // Redirect kembali ke halaman daftar dengan pesan sukses
        return redirect()->route('admin.pengaduan.index')->with('success', 'Pengaduan berhasil dihapus.'); //
    }
}
