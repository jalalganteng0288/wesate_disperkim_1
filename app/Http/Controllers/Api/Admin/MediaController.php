<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class MediaController extends Controller
{
    public function index()
    {
        return Media::with('uploader:id,name')->latest()->paginate(15);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => ['required', 'file', 'max:5120'], // Maksimal 5MB
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $file = $request->file('file');
        $path = $file->store('media', 'public'); // Simpan di storage/app/public/media

        $media = Media::create([
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $path,
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'uploaded_by' => auth()->id(), // Nanti diganti dengan auth()->id()
        ]);

        return response()->json([
            'message' => 'File uploaded successfully',
            'media' => $media
        ], 201);
    }

    public function destroy(Media $medium)
    {
        // Hapus file dari storage
        Storage::disk('public')->delete($medium->file_path);

        // Hapus record dari database
        $medium->delete();

        return response()->json(['message' => 'File deleted successfully']);
    }
}