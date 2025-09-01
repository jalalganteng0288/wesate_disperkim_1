<?php

namespace App\Http\Controllers\Api\Admin;


use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str; // <-- Tambahkan ini untuk membuat slug

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Ambil berita, sertakan data penulis, urutkan dari yang terbaru
        $news = News::with('author')->latest()->paginate(10);

        return response()->json($news);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255', 'unique:news,title'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'is_published' => ['required', 'boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $imageUrl = null;
        if ($request->hasFile('featured_image')) {
            // Simpan gambar ke 'storage/app/public/news'
            $imageUrl = $request->file('featured_image')->store('news', 'public');
        }

        // Buat berita baru
        $news = News::create([
            'author_id' => 1, // Nanti diganti dengan auth()->id()
            'title' => $request->title,
            'slug' => Str::slug($request->title), // Membuat slug otomatis dari judul
            'subtitle' => $request->subtitle,
            'content' => $request->content,
            'featured_image_url' => $imageUrl,
            'is_published' => $request->is_published,
            'publication_date' => $request->is_published ? now() : null, // Jika langsung publish, catat tanggalnya
        ]);

        return response()->json([
            'message' => 'News article created successfully',
            'news' => $news->load('author')
        ], 201);
    }

    // ... method lainnya ...
}