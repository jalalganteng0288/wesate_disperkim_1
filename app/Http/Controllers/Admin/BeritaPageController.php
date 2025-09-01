<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;

// PASTIKAN ANDA MENGGUNAKAN 'use' STATEMENT YANG BENAR INI
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class BeritaPageController extends Controller
{
    /**
     * Menampilkan halaman daftar semua berita.
     */
    public function index()
    {
        $beritas = Berita::with('user')->latest()->paginate(10);
        return view('admin.berita.index', compact('beritas'));
    }

    /**
     * Menampilkan form untuk membuat berita baru.
     */
    public function create()
    {
        return view('admin.berita.create');
    }

    /**
     * Menyimpan berita baru ke database.
     */
    public function store(Request $request)
    {
        // 1. Validasi
        $validatedData = $request->validate([
            'judul' => 'required|string|max:255|unique:beritas,judul',
            'konten' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        // 2. Tambahkan data tambahan
        $validatedData['user_id'] = Auth::id();
        $validatedData['slug'] = Str::slug($request->judul); // Membuat slug dari judul

        // 3. Set tanggal publikasi jika statusnya 'published'
        if ($request->status === 'published') {
            $validatedData['published_at'] = Carbon::now();
        } else {
            $validatedData['published_at'] = null; // Pastikan null jika statusnya draft
        }

        // 4. Simpan ke database
        Berita::create($validatedData);

        // 5. Redirect dengan pesan sukses
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil disimpan.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function show(Berita $berita)
    {
        // Nanti kita bisa buat halaman untuk melihat berita
        return redirect()->route('admin.berita.index');
    }

    /**
     * Menampilkan form untuk mengedit berita.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function edit(Berita $berita)
    {
        // Nanti kita akan buat ini
        return redirect()->route('admin.berita.index');
    }

    /**
     * Mengupdate berita di database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Berita $berita)
    {
         // 1. Validasi
        $validatedData = $request->validate([
            // Judul harus unik, kecuali untuk berita ini sendiri
            'judul' => 'required|string|max:255|unique:beritas,judul,' . $berita->id,
            'konten' => 'required|string',
            'status' => 'required|in:draft,published',
        ]);

        // 2. Update data tambahan
        $validatedData['slug'] = Str::slug($request->judul);

        // 3. Atur tanggal publikasi
        // Jika status diubah menjadi 'published' dan belum pernah ada tanggal publikasi
        if ($request->status === 'published' && is_null($berita->published_at)) {
            $validatedData['published_at'] = Carbon::now();
        } 
        // Jika status diubah kembali ke 'draft'
        elseif ($request->status === 'draft') {
            $validatedData['published_at'] = null;
        }

        // 4. Update data di database
        $berita->update($validatedData);

        // 5. Redirect dengan pesan sukses
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil diperbarui.');// Nanti kita akan buat ini
    }

    /**
     * Menghapus berita dari database.
     *
     * @param  \App\Models\Berita  $berita
     * @return \Illuminate\Http\Response
     */
    public function destroy(Berita $berita)
    {
        $berita->delete();
        return redirect()->route('admin.berita.index')->with('success', 'Berita berhasil dihapus.');
    }
}