<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Spatie\Permission\Models\Role;

class UserPageController extends Controller
{
    /**
     * Menampilkan halaman daftar semua pengguna.
     */
    public function index()
    {
        // Mengambil data user, beserta roles-nya, diurutkan dari yang terbaru, dan menggunakan paginasi.
        $users = User::with('roles')->latest()->paginate(10);

        // Mengirim data ke view Blade
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan halaman form untuk membuat user baru.
     */
    public function create()
    {
        // Ambil semua role untuk ditampilkan di dropdown form
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Menyimpan user baru dari form ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // Buat User Baru
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Berikan Role ke User
        $user->assignRole($request->role);

        // Redirect kembali ke halaman daftar user dengan pesan sukses
        return redirect()->route('admin.users.index')->with('success', 'User berhasil ditambahkan.');
    }

    /**
     * Menampilkan halaman form untuk mengedit user.
     * Laravel akan otomatis mencari user dari database berdasarkan ID di URL ($user).
     */
    public function edit(User $user)
    {
        // Ambil semua role untuk pilihan di form
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Mengupdate data user di database.
     */
    public function update(Request $request, User $user)
    {
        // Validasi input dari form edit
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'password' => ['nullable', 'confirmed', Password::defaults()], // Password tidak wajib diisi
            'role' => ['required', 'string', 'exists:roles,name'],
        ]);

        // Update data user
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        // Update password hanya jika field password diisi
        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        // Sinkronkan role (hapus role lama, ganti dengan yang baru)
        $user->syncRoles([$request->role]);

        return redirect()->route('admin.users.index')->with('success', 'User berhasil diperbarui.');
    }

    /**
     * Menghapus user dari database.
     */
    public function destroy(User $user)
    {
        // Cek agar user tidak bisa menghapus akunnya sendiri
        if (Auth::user()->id === $user->id) {
            return redirect()->route('admin.users.index')->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }
        
        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'User berhasil dihapus.');
    }
}