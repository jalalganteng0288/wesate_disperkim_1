<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class SettingPageController extends Controller
{
    /**
     * Menampilkan halaman utama pengaturan.
     */
    public function index()
    {
        $user = Auth::user();
        $settings = Setting::pluck('value', 'key')->all();

        return view('admin.pengaturan.index', compact('user', 'settings'));
    }

    // ========================================================================
    // === FUNGSI BARU DITAMBAHKAN DI SINI UNTUK MEMPERBAIKI ERROR ===
    // ========================================================================
    public function updateGeneral(Request $request)
    {
        $validatedData = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_description' => 'nullable|string',
            'app_logo' => ['nullable', 'image', 'mimes:jpeg,png,jpg,svg', 'max:2048'],
        ]);

        // Simpan nama aplikasi
        Setting::updateOrCreate(
            ['key' => 'app_name'],
            ['value' => $validatedData['app_name']]
        );

        // Simpan deskripsi aplikasi
        Setting::updateOrCreate(
            ['key' => 'app_description'],
            ['value' => $validatedData['app_description']]
        );

        // Handle upload logo
        if ($request->hasFile('app_logo')) {
            // Hapus logo lama jika ada
            $oldLogoPath = Setting::get('app_logo');
            if ($oldLogoPath && Storage::disk('public')->exists($oldLogoPath)) {
                Storage::disk('public')->delete($oldLogoPath);
            }

            // Simpan logo baru
            $path = $request->file('app_logo')->store('logos', 'public');
            Setting::updateOrCreate(['key' => 'app_logo'], ['value' => $path]);
        }

        return back()->with('status', 'pengaturan-umum-diperbarui');
    }
    // ========================================================================


    /**
     * Memperbarui informasi profil pengguna.
     */
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $validatedData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'profile_photo' => ['nullable', 'image', 'max:2048', 'mimes:jpeg,png,jpg,gif,svg'],
        ]);

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $path = $request->file('profile_photo')->store('profile-photos', 'public');
            $user->profile_photo_path = $path;
        }

        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->save();

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

    /**
     * Menghapus logo aplikasi.
     */
    public function deleteAppLogo(Request $request)
    {
        $logoSetting = Setting::where('key', 'app_logo')->first();

        if ($logoSetting) {
            if ($logoSetting->value && Storage::disk('public')->exists($logoSetting->value)) {
                Storage::disk('public')->delete($logoSetting->value);
            }
            $logoSetting->delete();
        }

        return back()->with('status', 'logo-dihapus');
    }

    /**
     * Memperbarui pengaturan notifikasi.
     */
    public function updateNotifications(Request $request)
    {
        $request->validate([
            'notifications_email' => 'nullable|string',
            'notifications_push' => 'nullable|string',
        ]);

        Setting::updateOrCreate(
            ['key' => 'notifications_email'],
            ['value' => $request->has('notifications_email') ? '1' : '0']
        );

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
        $request->validate([
            'theme_mode' => 'required|string|in:light,dark',
        ]);

        Setting::updateOrCreate(
            ['key' => 'theme_mode'],
            ['value' => $request->theme_mode]
        );

        return back()->with('status', 'tampilan-diperbarui');
    }
}
