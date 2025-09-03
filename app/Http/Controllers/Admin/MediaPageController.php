<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <-- PERBAIKAN UTAMA: Tambahkan baris ini

class MediaPageController extends Controller
{
    public function index()
    {
        $mediaItems = Media::latest()->paginate(15);
        return view('admin.media.index', compact('mediaItems'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,gif,pdf,doc,docx', 'max:5120'], // Maks 5MB
        ]);

        $file = $request->file('file');
        // Simpan file ke storage/app/public/media
        $path = $file->store('media', 'public');

        Media::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploader_id' => Auth::id(), // Sekarang baris ini sudah benar
        ]);

        return back()->with('success', 'File berhasil di-upload.');
    }

    public function destroy(Media $media)
    {
        // Hapus file dari disk
        Storage::disk('public')->delete($media->file_path);

        // Hapus record dari database
        $media->delete();

        return back()->with('success', 'File berhasil dihapus.');
    }
} 