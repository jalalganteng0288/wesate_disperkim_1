<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // <-- PENTING: Gunakan facade Auth
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class BeritaPageController extends Controller
{
    /**
     * Menampilkan halaman daftar berita.
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
        $request->validate([
            'judul' => 'required|string|max:255|unique:beritas,judul',
            'konten' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('berita_images', 'public');
        }

        Berita::create([
            'user_id' => Auth::id(), // <-- PERBAIKAN: Menggunakan Auth::id()
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'konten' => $request->konten,
            'image_path' => $imagePath,
            'status' => 'published', // Langsung publish
            'published_at' => now(),
        ]);

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil ditambahkan.');
    }


    /**
     * Menampilkan form untuk mengedit berita.
     */
    public function edit(Berita $beritum)
    {
        return view('admin.berita.edit', ['berita' => $beritum]);
    }

    /**
     * Memperbarui data berita di database.
     */
    public function update(Request $request, Berita $beritum)
    {
        $request->validate([
            'judul' => 'required|string|max:255|unique:beritas,judul,' . $beritum->id,
            'konten' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $imagePath = $beritum->image_path;
        if ($request->hasFile('image')) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('image')->store('berita_images', 'public');
        }

        $beritum->update([
            'judul' => $request->judul,
            'slug' => Str::slug($request->judul),
            'konten' => $request->konten,
            'image_path' => $imagePath,
        ]);

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil diperbarui.');
    }

    /**
     * Menghapus berita dari database.
     */
    public function destroy(Berita $beritum)
    {
        if ($beritum->image_path) {
            Storage::disk('public')->delete($beritum->image_path);
        }

        $beritum->delete();

        return redirect()->route('admin.berita.index')
                         ->with('success', 'Berita berhasil dihapus.');
    }
}