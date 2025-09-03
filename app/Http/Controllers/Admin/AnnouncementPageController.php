<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini

class AnnouncementPageController extends Controller
{
    public function index()
    {
        $announcements = Announcement::latest()->paginate(10);
        return view('admin.pengumuman.index', compact('announcements'));
    }

    public function create()
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'placement' => 'required|string|in:banner,popup',
            'is_published' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validasi gambar
        ]);

        // Handle upload gambar
        if ($request->hasFile('image')) {
            $validatedData['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $validatedData['user_id'] = Auth::id();
        Announcement::create($validatedData);

        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil dibuat.');
    }

    public function edit(Announcement $pengumuman)
    {
        return view('admin.pengumuman.edit', compact('pengumuman'));
    }

    public function update(Request $request, Announcement $pengumuman)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'placement' => 'required|string|in:banner,popup',
            'is_published' => 'required|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        // Handle jika ada gambar baru yang di-upload
        if ($request->hasFile('image')) {
            // Hapus gambar lama jika ada
            if ($pengumuman->image_path) {
                Storage::disk('public')->delete($pengumuman->image_path);
            }
            // Simpan gambar baru
            $validatedData['image_path'] = $request->file('image')->store('announcements', 'public');
        }

        $pengumuman->update($validatedData);

        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Announcement $pengumuman)
    {
        // Hapus gambar terkait dari storage saat pengumuman dihapus
        if ($pengumuman->image_path) {
            Storage::disk('public')->delete($pengumuman->image_path);
        }

        $pengumuman->delete();
        return redirect()->route('admin.pengumuman.index')
                         ->with('success', 'Pengumuman berhasil dihapus.');
    }
}