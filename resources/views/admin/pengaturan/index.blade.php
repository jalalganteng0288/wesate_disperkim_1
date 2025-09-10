<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pengaturan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- ====================================================== --}}
            {{-- BAGIAN NOTIFIKASI (SUDAH DIPERBAIKI & DILENGKAPI) --}}
            {{-- ====================================================== --}}
            @if (session('status'))
            <div class="alert-success mb-4" role="alert">
                @if (session('status') === 'profil-diperbarui')
                <span class="font-medium">Profil berhasil diperbarui.</span>
                @elseif (session('status') === 'password-diperbarui')
                <span class="font-medium">Kata sandi berhasil diperbarui.</span>
                @elseif (session('status') === 'foto-profil-dihapus')
                <span class="font-medium">Foto profil berhasil dihapus.</span>
                @elseif (session('status') === 'pengaturan-umum-diperbarui')
                <span class="font-medium">Pengaturan umum berhasil diperbarui.</span>
                @elseif (session('status') === 'logo-dihapus')
                <span class="font-medium">Logo aplikasi berhasil dihapus.</span>
                {{-- INI PENAMBAHANNYA --}}
                @elseif (session('status') === 'notifikasi-diperbarui')
                <span class="font-medium">Pengaturan notifikasi berhasil disimpan.</span>
                @elseif (session('status') === 'tampilan-diperbarui')
                <span class="font-medium">Pengaturan tampilan berhasil disimpan.</span>
                {{-- AKHIR PENAMBAHAN --}}
                @else
                <span class="font-medium">{{ session('status') }}</span>
                @endif
            </div>
            @endif

            {{-- Notifikasi Error (jika ada kesalahan validasi) --}}
            @if ($errors->any())
            <div class="alert-error mb-4" role="alert">
                <span class="font-medium">Terjadi kesalahan:</span>
                <ul class="mt-1 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif


            <div class="settings-container">
                {{-- KIRI: NAVIGASI TAB --}}
                <nav class="settings-nav">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active" href="#" data-tab="umum">
                                <i class="fa-solid fa-gears"></i>
                                <span>Umum</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="profil">
                                <i class="fa-solid fa-user"></i>
                                <span>Profil</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="keamanan">
                                <i class="fa-solid fa-shield-halved"></i>
                                <span>Keamanan Akun</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="notifikasi">
                                <i class="fa-solid fa-bell"></i>
                                <span>Notifikasi</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#" data-tab="tampilan">
                                <i class="fa-solid fa-desktop"></i>
                                <span>Tampilan</span>
                            </a>
                        </li>
                    </ul>
                </nav>

                {{-- KANAN: KONTEN TAB --}}
                <div class="settings-content">

                    {{-- 1. KONTEN TAB UMUM --}}
                    <div id="umum-content" class="settings-card tab-content">
                        <h3>Pengaturan Umum Aplikasi</h3>
                        <p>Kelola pengaturan dasar aplikasi seperti nama, deskripsi, dan logo.</p>

                        <form method="post" action="{{ route('admin.pengaturan.updateGeneral') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            {{-- Nama Aplikasi --}}
                            <div>
                                <x-input-label for="app_name" value="{{ __('Nama Aplikasi') }}" class="input-label" />
                                <x-text-input id="app_name" name="app_name" type="text" class="mt-1 block w-full text-input" value="{{ old('app_name', $settings['app_name'] ?? config('app.name')) }}" required autocomplete="off" />
                                <x-input-error class="mt-2" :messages="$errors->get('app_name')" />
                            </div>

                            {{-- Deskripsi Singkat Aplikasi --}}
                            <div class="mt-4">
                                <x-input-label for="app_description" value="{{ __('Deskripsi Singkat Aplikasi') }}" class="input-label" />
                                <textarea id="app_description" name="app_description" rows="3" class="mt-1 block w-full text-input resize-y">{{ old('app_description', $settings['app_description'] ?? '') }}</textarea>
                                <x-input-error class="mt-2" :messages="$errors->get('app_description')" />
                            </div>

                            {{-- Logo Aplikasi --}}
                            <div class="mt-4">
                                <x-input-label for="app_logo_input" value="{{ __('Logo Aplikasi') }}" class="input-label" />
                                <div class="flex items-center gap-4">
                                    <div id="app_logo_preview" class="current-avatar">
                                        @if(isset($settings['app_logo']) && Storage::disk('public')->exists($settings['app_logo']))
                                        <img src="{{ Storage::url($settings['app_logo']) }}" alt="App Logo" class="rounded-full w-full h-full object-contain">
                                        @else
                                        <span class="font-bold text-2xl">A</span>
                                        @endif
                                    </div>
                                    <div class="avatar-actions">
                                        <input type="file" id="app_logo_input" name="app_logo" class="hidden" accept="image/png, image/jpeg, image/svg+xml">
                                        <button type="button" onclick="document.getElementById('app_logo_input').click()">Ganti Logo</button>
                                        @if(isset($settings['app_logo']))
                                        <button type="button" onclick="document.getElementById('delete_logo_form').submit()" class="text-red-600 hover:text-red-900">Hapus Logo</button>
                                        @endif
                                        <small>Disarankan PNG transparan atau SVG. Maks 2MB.</small>
                                    </div>
                                </div>
                                <x-input-error class="mt-2" :messages="$errors->get('app_logo')" />
                            </div>

                            <div class="flex justify-end items-center mt-6">
                                <button type="submit" class="btn-simpan-modern">{{ __('Simpan Perubahan') }}</button>
                            </div>
                        </form>

                        <form id="delete_logo_form" method="post" action="{{ route('admin.pengaturan.deleteAppLogo') }}" class="hidden">
                            @csrf
                            @method('delete')
                        </form>
                    </div>

                    {{-- 2. KONTEN TAB PROFIL --}}
                    <div id="profil-content" class="settings-card tab-content" style="display: none;">
                        <h3>Profil Publik</h3>
                        <p>Informasi ini akan ditampilkan di profil Anda.</p>

                        <form method="post" action="{{ route('admin.pengaturan.updateProfile') }}" enctype="multipart/form-data" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            {{-- Foto Profil --}}
                            <div class="avatar-section">
                                <div id="profile_photo_preview" class="current-avatar">
                                    @if ($user->profile_photo_path)
                                    <img src="{{ Storage::url($user->profile_photo_path) }}" alt="{{ $user->name }}" class="rounded-full object-cover w-full h-full">
                                    @else
                                    <span class="font-bold text-2xl">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                    @endif
                                </div>
                                <div class="avatar-actions">
                                    <input type="file" id="profile_photo_input" name="profile_photo" class="hidden" accept="image/png, image/jpeg, image/jpg, image/gif, image/svg+xml">
                                    <button type="button" onclick="document.getElementById('profile_photo_input').click()">Ganti Foto</button>
                                    @if ($user->profile_photo_path)
                                    <button type="button" onclick="document.getElementById('delete_profile_photo_form').submit()" class="text-red-600 hover:text-red-900">Hapus Foto</button>
                                    @endif
                                    <small>PNG, JPG, atau SVG. Maks 2MB.</small>
                                </div>
                            </div>

                            {{-- Nama Lengkap --}}
                            <div>
                                <x-input-label for="name" :value="__('Nama Lengkap')" class="input-label" />
                                <x-text-input id="name" name="name" type="text" class="mt-1 block w-full text-input" :value="old('name', $user->name)" required autofocus autocomplete="name" />
                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            {{-- Email --}}
                            <div>
                                <x-input-label for="email" :value="__('Email')" class="input-label" />
                                <x-text-input id="email" name="email" type="email" class="mt-1 block w-full text-input" :value="old('email', $user->email)" required autocomplete="username" />
                                <x-input-error class="mt-2" :messages="$errors->get('email')" />
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="btn-simpan-modern">{{ __('Simpan Perubahan Profil') }}</button>
                            </div>
                        </form>

                        <form id="delete_profile_photo_form" method="post" action="{{ route('admin.pengaturan.deleteProfilePhoto') }}" class="hidden">
                            @csrf
                            @method('delete')
                        </form>
                    </div>

                    {{-- 3. KONTEN TAB KEAMANAN --}}
                    <div id="keamanan-content" class="settings-card tab-content" style="display: none;">
                        <h3>Keamanan Akun</h3>
                        <p>Pastikan akun Anda menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>

                        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('put')

                            <div>
                                <x-input-label for="current_password" :value="__('Kata Sandi Saat Ini')" class="input-label" />
                                <x-text-input id="current_password" name="current_password" type="password" class="mt-1 block w-full text-input" autocomplete="current-password" />
                                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password" :value="__('Kata Sandi Baru')" class="input-label" />
                                <x-text-input id="password" name="password" type="password" class="mt-1 block w-full text-input" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2" />
                            </div>

                            <div>
                                <x-input-label for="password_confirmation" :value="__('Konfirmasi Kata Sandi Baru')" class="input-label" />
                                <x-text-input id="password_confirmation" name="password_confirmation" type="password" class="mt-1 block w-full text-input" autocomplete="new-password" />
                                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="btn-simpan-modern">{{ __('Simpan Kata Sandi') }}</button>

                                @if (session('status') === 'password-updated')
                                <p
                                    x-data="{ show: true }"
                                    x-show="show"
                                    x-transition
                                    x-init="setTimeout(() => show = false, 2000)"
                                    class="text-sm text-gray-600 ml-4">{{ __('Tersimpan.') }}</p>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- ========================================================== --}}
                    {{-- 4. KONTEN TAB NOTIFIKASI (INI YANG DIPERBARUI) --}}
                    {{-- ========================================================== --}}
                    <div id="notifikasi-content" class="settings-card tab-content" style="display: none;">
                        <h3>Pengaturan Notifikasi</h3>
                        <p>Kelola bagaimana Anda menerima pemberitahuan dari aplikasi.</p>

                        <form method="post" action="{{ route('admin.pengaturan.updateNotifications') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="notifications_email" name="notifications_email" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                        @checked(old('notifications_email', $settings['notifications_email'] ?? false))>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notifications_email" class="font-medium text-gray-700">Notifikasi Email</label>
                                    <p class="text-gray-500">Dapatkan email pemberitahuan untuk setiap aktivitas penting.</p>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="notifications_push" name="notifications_push" type="checkbox" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded"
                                        @checked(old('notifications_push', $settings['notifications_push'] ?? false))>
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="notifications_push" class="font-medium text-gray-700">Notifikasi Push (Browser)</label>
                                    <p class="text-gray-500">Dapatkan notifikasi langsung di browser Anda (segera hadir).</p>
                                </div>
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="btn-simpan-modern">{{ __('Simpan Notifikasi') }}</button>
                            </div>
                        </form>
                    </div>

                    {{-- ======================================================== --}}
                    {{-- 5. KONTEN TAB TAMPILAN (INI YANG DIPERBARUI) --}}
                    {{-- ======================================================== --}}
                    <div id="tampilan-content" class="settings-card tab-content" style="display: none;">
                        <h3>Pengaturan Tampilan</h3>
                        <p>Sesuaikan tampilan antarmuka aplikasi sesuai preferensi Anda.</p>

                        <form method="post" action="{{ route('admin.pengaturan.updateAppearance') }}" class="mt-6 space-y-6">
                            @csrf
                            @method('patch')

                            <div>
                                <x-input-label for="theme_mode" value="{{ __('Mode Tema') }}" class="input-label" />
                                <select id="theme_mode" name="theme_mode" class="mt-1 block w-full text-input">
                                    <option value="light" @selected(old('theme_mode', $settings['theme_mode'] ?? 'light' )=='light' )>Terang (Default)</option>
                                    <option value="dark" @selected(old('theme_mode', $settings['theme_mode'] ?? 'light' )=='dark' ) disabled>Gelap (Segera Hadir)</option>
                                </select>
                                <x-input-error class="mt-2" :messages="$errors->get('theme_mode')" />
                            </div>

                            <div class="flex items-center justify-end">
                                <button type="submit" class="btn-simpan-modern">{{ __('Simpan Tampilan') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Logika Navigasi Tab (Sudah benar, tidak perlu diubah)
            const navLinks = document.querySelectorAll('.settings-nav .nav-link');
            const tabContents = document.querySelectorAll('.tab-content');

            function showTabFromHash() {
                const hash = window.location.hash.substring(1);
                const defaultTab = 'umum';
                const targetTab = hash || defaultTab;
                navLinks.forEach(link => {
                    link.classList.toggle('active', link.getAttribute('data-tab') === targetTab);
                });
                tabContents.forEach(content => {
                    content.style.display = content.id === `${targetTab}-content` ? 'block' : 'none';
                });
            }

            navLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    history.pushState(null, null, `#${tabId}`);
                    showTabFromHash();
                });
            });

            showTabFromHash();
            window.addEventListener('hashchange', showTabFromHash);

            // ==========================================================
            // JAVASCRIPT PREVIEW GAMBAR (INI YANG DIPERBAIKI)
            // ==========================================================
            function setupImagePreview(inputId, previewContainerId) {
                const inputElement = document.getElementById(inputId);
                const previewElement = document.getElementById(previewContainerId);

                // PENGECEKAN: Hanya jalankan jika elemennya ada!
                if (!inputElement || !previewElement) {
                    return; // Hentikan fungsi jika elemen tidak ditemukan
                }

                inputElement.addEventListener('change', function(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewElement.innerHTML = `<img src="${e.target.result}" class="rounded-full object-cover w-full h-full">`;
                        };
                        reader.readAsDataURL(file);
                    }
                });
            }

            // Inisialisasi preview untuk kedua form
            setupImagePreview('profile_photo_input', 'profile_photo_preview');
            setupImagePreview('app_logo_input', 'app_logo_preview');
        });
    </script>
    @endpush
</x-app-layout>