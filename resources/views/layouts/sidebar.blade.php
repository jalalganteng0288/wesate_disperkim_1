<aside class="sidebar bg-light border-end" style="width: 280px; height: 100vh;">
    <div class="p-4">
        <h4>Disperkim Garut</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                Manajemen Pengguna
            </a>
        </li>
        {{-- TAMBAHKAN BLOK INI --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}" href="{{ route('admin.pengaduan.index') }}">
                Pengaduan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.infrastruktur.*') ? 'active' : '' }}" href="{{ route('admin.infrastruktur.index') }}"> Laporan Infrastruktur
            </a>
        </li>
        {{-- AKHIR BLOK TAMBAHAN --}}
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                Berita
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.perumahan.*') ? 'active' : '' }}" href="{{ route('admin.perumahan.index') }}">
                Master Perumahan
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}" href="{{ route('admin.pengumuman.index') }}">
                Pengumuman
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.media.index') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                Media Library
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.map.index') ? 'active' : '' }}" href="{{ route('admin.map.index') }}">
                Peta & Analitik
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}" href="{{ route('admin.penugasan.index') }}">
                Penugasan (Work Order)
            </a>
        </li>
    </ul>
</aside>