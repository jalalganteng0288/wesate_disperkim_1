<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;       // <-- Pastikan ini ada
use Illuminate\Support\Facades\Validator;   // <-- Pastikan ini ada
use Illuminate\Validation\Rules\Password;   // <-- Pastikan ini ada
use Spatie\Permission\Models\Role;          // <-- Pastikan ini ada

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Kode ini sekarang berada di tempat yang benar.
        // Mengambil semua user, beserta roles-nya, dan menggunakan paginasi.
        $users = User::with('roles')->paginate(10);

        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'], // Pastikan role ada di tabel roles
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // 422 Unprocessable Entity
        }

        // 2. Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // 3. Berikan Role ke User
        $user->assignRole($request->role);

        // 4. Kembalikan Respons Sukses
        return response()->json([
            'message' => 'User created successfully',
            'user' => $user->load('roles') // Muat role untuk ditampilkan di respons
        ], 201); // 201 Created
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        // Method ini akan kita isi nanti untuk menampilkan satu user
        // Untuk sekarang, kita bisa kembalikan data user yang diminta
        // beserta rolenya.
        return response()->json($user->load('roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // 1. Validasi Input
        $validator = Validator::make($request->all(), [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            // Pastikan email unik, kecuali untuk user saat ini
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Password tidak wajib diisi saat update, tapi jika diisi, harus ada konfirmasi
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'role' => ['sometimes', 'required', 'string', 'exists:roles,name'],
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // 2. Update data user
        $user->update([
            'name' => $request->filled('name') ? $request->name : $user->name,
            'email' => $request->filled('email') ? $request->email : $user->email,
        ]);

        // 3. Update password jika diisi
        if ($request->filled('password')) {
            $user->update([
                'password' => Hash::make($request->password),
            ]);
        }

        // 4. Update role jika diisi
        if ($request->filled('role')) {
            // Menggunakan syncRoles untuk menghapus role lama dan menetapkan yang baru
            $user->syncRoles([$request->role]);
        }

        // 5. Kembalikan Respons Sukses
        return response()->json([
            'message' => 'User updated successfully',
            'user' => $user->load('roles') // Muat role terbaru untuk ditampilkan
        ]);// Kita akan isi ini nanti
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
       // Cek agar user tidak bisa menghapus dirinya sendiri
        if (\Illuminate\Support\Facades\Auth::user()->id === $user->id) {
            return response()->json(['message' => 'You cannot delete your own account.'], 403); // 403 Forbidden
        }
        
        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200); // Kita akan isi ini nanti
    }
    
}