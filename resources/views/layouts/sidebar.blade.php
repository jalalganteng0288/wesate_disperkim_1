{{-- Ganti semua isi resources/views/layouts/sidebar.blade.php dengan ini --}}
<aside class="sidebar bg-light border-end" style="width: 280px; height: 100vh;">
    <div class="p-4 sidebar-header flex items-center gap-3">
        {{-- Logo DG (Sudah ada di desain Anda) --}}
        <div class="bg-blue-600 text-white p-2 rounded-md font-bold text-lg flex items-center justify-center" style="width: 40px; height: 40px;">
            DG
        </div>
        <div>
            <h4 class="mb-0" style="font-size: 1.1rem; line-height: 1.2;">DISPERKIM GARUT</h4>
            <span class="text-sm opacity-70">Sistem Informasi</span>
        </div>
    </div>

    <ul class="nav flex-column px-3 pt-2">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i data-lucide="layout-grid"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i data-lucide="users"></i>
                <span>Pengguna</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}" href="{{ route('admin.pengaduan.index') }}">
                <i data-lucide="bell-ring"></i>
                <span>Pengaduan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.infrastruktur.*') ? 'active' : '' }}" href="{{ route('admin.infrastruktur.index') }}">
                <i data-lucide="building"></i>
                <span>Infrastruktur</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.perumahan.*') ? 'active' : '' }}" href="{{ route('admin.perumahan.index') }}">
                <i data-lucide="home"></i>
                <span>Perumahan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.kecamatan.*') ? 'active' : '' }}" href="{{ route('admin.kecamatan.index') }}">
                <i data-lucide="map"></i>
                <span>Data Kecamatan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                <i data-lucide="newspaper"></i>
                <span>Berita</span>
            </a>
        </li>
        <li class="nav-item">
            {{-- Asumsi "Laporan" mengarah ke Penugasan/Work Order --}}
            <a class="nav-link {{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}" href="{{ route('admin.penugasan.index') }}">
                <i data-lucide="file-text"></i>
                <span>Laporan</span>
            </a>
        </li>
        <li class="nav-item">
            {{-- Asumsi "Statistik" mengarah ke Dashboard atau Peta (kita arahkan ke Peta untuk data analitik) --}}
            <a class="nav-link" href="{{ route('admin.map.index') }}">
                <i data-lucide="bar-chart-3"></i>
                <span>Statistik</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" href="{{ route('admin.pengaturan.index') }}">
                <i data-lucide="settings"></i>
                <span>Pengaturan</span>
            </a>
        </li>
        {{-- Item Media Library dihapus (karena tidak ada di gambar target) --}}
    </ul>

    {{-- Tombol Logout di Bawah (sesuai gambar target) --}}
    <div class="p-4 mt-auto absolute bottom-0 w-full">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full bg-red-600 hover:bg-red-700 text-white font-bold py-3 px-4 rounded-lg flex items-center justify-center gap-2 transition-all duration-200">
                <i data-lucide="log-out" class="w-5 h-5"></i>
                <span>Logout</span>
            </button>
        </form>
    </div>

    {{-- Script untuk menjalankan Lucide --}}
    @push('scripts')
    <script>
        lucide.createIcons();
    </script>
    @endpush
</aside>