<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengaduan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Notifications\ComplaintStatusUpdated;

class ComplaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $complaints = Pengaduan::with('user')->latest()->paginate(10);

        return response()->json($complaints);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'], // Foto tidak wajib, tapi jika ada harus gambar maks 2MB
            'latitude' => ['required', 'numeric'],
            'longitude' => ['required', 'numeric'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            // Simpan foto ke 'storage/app/public/complaints'
            // dan dapatkan path-nya untuk disimpan di database
            $photoPath = $request->file('photo')->store('complaints', 'public');
        }

        // Buat pengaduan baru
        $complaint = Pengaduan::create([
            // Untuk sementara, kita hardcode user_id ke 1 (Super Admin).
            // Nanti ini akan diganti dengan ID user yang sedang login.
            'user_id' => 1,
            'title' => $request->title,
            'description' => $request->description,
            'photo_url' => $photoPath,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'status' => 'Baru Masuk', // Status default
        ]);

        return response()->json([
            'message' => 'Complaint created successfully',
            'complaint' => $complaint->load('user')
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Pengaduan $complaint)
    {
        // Untuk menampilkan detail satu pengaduan
        return response()->json($complaint->load('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pengaduan $complaint)
    {
        $validator = Validator::make($request->all(), [
            'title' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'required', 'string'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
            'latitude' => ['sometimes', 'required', 'numeric'],
            'longitude' => ['sometimes', 'required', 'numeric'],
            // Kita akan tambahkan validasi status nanti
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Ambil semua data yang tervalidasi
        $validatedData = $validator->validated();

        // Cek jika ada file foto baru
        if ($request->hasFile('photo')) {
            // 1. Hapus foto lama jika ada
            if ($complaint->photo_url) {
                Storage::disk('public')->delete($complaint->photo_url);
            }

            // 2. Simpan foto baru dan update path di data
            $validatedData['photo_url'] = $request->file('photo')->store('complaints', 'public');
        }

        // Lakukan update pada data pengaduan
        $complaint->update($validatedData);

        return response()->json([
            'message' => 'Complaint updated successfully',
            'complaint' => $complaint->load('user')
        ]); // Kita akan isi ini di langkah berikutnya
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pengaduan $complaint)
    {
        if ($complaint->photo_url) {
            Storage::disk('public')->delete($complaint->photo_url);
        }

        $complaint->delete();

        return response()->json(['message' => 'Complaint deleted successfully']); // Kita akan isi ini di langkah berikutnya
    }
    public function updateStatus(Request $request, Pengaduan $complaint)
    {
        // 1. Validasi Input Status
        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string', 'in:Sedang Diverifikasi,Dalam Pengerjaan,Selesai Dikerjakan,Ditolak'],
            'notes' => ['nullable', 'string'], // Catatan opsional dari admin
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Simpan status lama untuk histori
        $oldStatus = $complaint->status;
        $newStatus = $request->status;

        // 2. Update status di tabel complaints
        $complaint->update([
            'status' => $newStatus,
        ]);

        // 3. (PENTING) Catat perubahan ke dalam tabel histori
        $complaint->complaintHistories()->create([
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
            'notes' => $request->notes,
            'user_id' => 1, // Nanti diganti dengan auth()->id()
        ]);

        $reporter = $complaint->user;

        // 2. Kirim notifikasi jika user-nya ada
        if ($reporter) {
            // Mungkin baris ini yang error
            $reporter->notify(new ComplaintStatusUpdated($pengaduan));
        }
        // --- AKHIR BAGIAN BARU ---

        return response()->json([
            'message' => 'Complaint status updated successfully',
            'complaint' => $complaint->load('user'),
        ]);

        // 4. Kirim notifikasi (akan kita implementasikan nanti)
        // TODO: Kirim notifikasi ke pelapor

        return response()->json([
            'message' => 'Complaint status updated successfully',
            'complaint' => $complaint->load('user'),
        ]);
    }
}
