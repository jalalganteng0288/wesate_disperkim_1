<aside class="sidebar bg-light border-end" style="width: 280px; height: 100vh;">
    <div class="p-4">
        <h4>Disperkim Garut</h4>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <i class="fa-solid fa-gauge-high fa-fw me-2"></i>
                <span>Dashboard</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" href="{{ route('admin.users.index') }}">
                <i class="fa-solid fa-users fa-fw me-2"></i>
                <span>Manajemen Pengguna</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengaduan.*') ? 'active' : '' }}" href="{{ route('admin.pengaduan.index') }}">
                <i class="fa-solid fa-bullhorn fa-fw me-2"></i>
                <span>Pengaduan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.infrastruktur.*') ? 'active' : '' }}" href="{{ route('admin.infrastruktur.index') }}">
                <i class="fa-solid fa-road-bridge fa-fw me-2"></i>
                <span>Laporan Infrastruktur</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.berita.*') ? 'active' : '' }}" href="{{ route('admin.berita.index') }}">
                <i class="fa-solid fa-newspaper fa-fw me-2"></i>
                <span>Berita</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.perumahan.*') ? 'active' : '' }}" href="{{ route('admin.perumahan.index') }}">
                <i class="fa-solid fa-house-chimney fa-fw me-2"></i>
                <span>Master Perumahan</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengumuman.*') ? 'active' : '' }}" href="{{ route('admin.pengumuman.index') }}">
                <i class="fa-solid fa-clipboard-list fa-fw me-2"></i>
                <span>Pengumuman</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.media.index') ? 'active' : '' }}" href="{{ route('admin.media.index') }}">
                <i class="fa-solid fa-photo-film fa-fw me-2"></i>
                <span>Media Library</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.map.index') ? 'active' : '' }}" href="{{ route('admin.map.index') }}">
                <i class="fa-solid fa-map-location-dot fa-fw me-2"></i>
                <span>Peta & Analitik</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.penugasan.*') ? 'active' : '' }}" href="{{ route('admin.penugasan.index') }}">
                <i class="fa-solid fa-list-check fa-fw me-2"></i>
                <span>Penugasan (Work Order)</span>
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ request()->routeIs('admin.pengaturan.*') ? 'active' : '' }}" href="{{ route('admin.pengaturan.index') }}">
                <i class="fa-solid fa-gear fa-fw me-2"></i>
                <span>Pengaturan</span>
            </a>
        </li>
    </ul>
</aside>