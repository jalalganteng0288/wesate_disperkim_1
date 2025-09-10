<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;  // <-- Tambahkan ini

class SettingPageController extends Controller
{
    /**
     * Menampilkan halaman utama pengaturan.
     */
    public function index()
    {
        $user = Auth::user();
        // Ambil semua settings dari database dan ubah menjadi array [key => value]
        $settings = Setting::pluck('value', 'key')->all();

        return view('admin.pengaturan.index', compact('user', 'settings'));
    }

    /**
     * Memperbarui informasi profil pengguna.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg'], // Validasi untuk foto
        ]);

        // Handle Photo Upload
        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Simpan foto baru
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        // Update data profil lainnya
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->save(); // Menggunakan save() karena kita mengupdate satu per satu

        return back()->with('status', 'profil-diperbarui');
    }

    /**
     * Memperbarui kata sandi pengguna.
     */
    public function updatePassword(Request $request)
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-diperbarui');
    }

    /**
     * Menghapus foto profil pengguna.
     */
    public function deleteProfilePhoto(Request $request)
    {
        $user = $request->user();

        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
            $user->profile_photo_path = null;
            $user->save();
        }

        return back()->with('status', 'foto-profil-dihapus');
    }
    public function deleteAppLogo(Request $request)
    {
        $logoSetting = Setting::where('key', 'app_logo')->first();

        if ($logoSetting) {
            // Hapus file dari storage
            if ($logoSetting->value && Storage::disk('public')->exists($logoSetting->value)) {
                Storage::disk('public')->delete($logoSetting->value);
            }
            // Hapus record dari database
            $logoSetting->delete();
        }

        return back()->with('status', 'logo-dihapus');
    }
    public function updateNotifications(Request $request)
    {
        // Validasi data (opsional, tapi disarankan)
        $request->validate([
            'notifications_email' => 'nullable|string',
            'notifications_push' => 'nullable|string',
        ]);

        // Simpan pengaturan notifikasi email
        Setting::updateOrCreate(
            ['key' => 'notifications_email'],
            ['value' => $request->has('notifications_email') ? '1' : '0']
        );

        // Simpan pengaturan notifikasi push
        Setting::updateOrCreate(
            ['key' => 'notifications_push'],
            ['value' => $request->has('notifications_push') ? '1' : '0']
        );

        return back()->with('status', 'notifikasi-diperbarui');
    }

    /**
     * Memperbarui pengaturan tampilan.
     */
    public function updateAppearance(Request $request)
    {
        // Validasi data
        $request->validate([
            'theme_mode' => 'required|string|in:light,dark',
        ]);

        // Simpan pengaturan tema
        Setting::updateOrCreate(
            ['key' => 'theme_mode'],
            ['value' => $request->theme_mode]
        );

        return back()->with('status', 'tampilan-diperbarui');
    }
}
