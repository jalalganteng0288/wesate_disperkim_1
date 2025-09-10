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
                        {{-- ... KODE UNTUK TAB UMUM BIARKAN SEPERTI SEBELUMNYA ... --}}
                    </div>

                    {{-- 2. KONTEN TAB PROFIL --}}
                    <div id="profil-content" class="settings-card tab-content" style="display: none;">
                        {{-- ... KODE UNTUK TAB PROFIL BIARKAN SEPERTI SEBELUMNYA ... --}}
                    </div>

                    {{-- 3. KONTEN TAB KEAMANAN --}}
                    <div id="keamanan-content" class="settings-card tab-content" style="display: none;">
                        @include('profile.partials.update-password-form')
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
                                    <option value="light" @selected(old('theme_mode', $settings['theme_mode'] ?? 'light') == 'light')>Terang (Default)</option>
                                    <option value="dark" @selected(old('theme_mode', $settings['theme_mode'] ?? 'light') == 'dark') disabled>Gelap (Segera Hadir)</option>
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
        document.addEventListener('DOMContentLoaded', function () {
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
                link.addEventListener('click', function (e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    history.pushState(null, null, `#${tabId}`);
                    showTabFromHash();
                });
            });

            showTabFromHash();
            window.addEventListener('hashchange', showTabFromHash);

            // JAVASCRIPT PREVIEW GAMBAR (Sudah benar, tidak perlu diubah)
            function setupImagePreview(inputId, previewContainerId) {
                // ... (fungsi ini biarkan seperti sebelumnya) ...
            }

            setupImagePreview('profile_photo_input', 'profile_photo_preview');
            setupImagePreview('app_logo_input', 'app_logo_preview');
        });
    </script>
    @endpush
</x-app-layout>