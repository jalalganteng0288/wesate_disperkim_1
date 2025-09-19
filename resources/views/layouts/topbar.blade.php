{{--
    File: resources/views/layouts/topbar.blade.php
    (Ganti seluruh isinya dengan kode ini)
--}}
<nav class="topbar"
    style="margin-left: 280px; width: calc(100% - 280px); height: 64px; background-color: #fff; border-bottom: 1px solid #e5e7eb; padding: 0 1.5rem; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 990;">

    <div class="topbar-left flex items-center gap-4">
        {{-- Tombol Hamburger Baru --}}
        <button class="hamburger-btn p-2 rounded-md hover:bg-gray-100 text-gray-600">
            <i data-lucide="menu" class="w-5 h-5"></i>
        </button>

        {{-- Search Bar Baru (Sesuai Target) --}}
        <div class="search-bar relative">
            <i data-lucide="search" class="text-gray-400"
                style="position: absolute; left: 12px; top: 50%; transform: translateY(-50%); width: 18px; height: 18px;"></i>
            <input type="text" placeholder="Cari pengaduan, laporan..."
                style="width: 320px; padding: 0.7rem 1rem 0.7rem 2.75rem; border-radius: 0.5rem; border: 1px solid #d1d5db; background-color: #f9fafb; font-size: 0.9rem;"
                class="focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition duration-200">
        </div>
    </div>

    <div class="topbar-right flex items-center gap-4">

        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open"
                class="notification-btn relative flex items-center justify-center w-10 h-10 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                <i data-lucide="bell" class="text-gray-600 w-5 h-5"></i>
                @if ($unreadNotificationsCount > 0)
                <span
                    class="notification-badge absolute -top-1 -right-1 flex items-center justify-center w-5 h-5 bg-red-500 text-white text-xs font-bold rounded-full">
                    {{ $unreadNotificationsCount }}
                </span>
                @endif
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="notification-dropdown absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl border border-gray-200 overflow-hidden"
                style="display: none; z-index: 1000;">

                <div class="dropdown-header p-4 border-b border-gray-200">
                    <h6 class="font-semibold text-gray-800">Notifikasi</h6>
                </div>

                <div class="dropdown-body" style="max-height: 300px; overflow-y: auto;">
                    @forelse ($unreadNotifications as $notification)
                    <a href="{{ $notification->data['link'] ?? route('admin.dashboard') }}"
                        class="notification-item flex gap-3 p-4 hover:bg-gray-50 transition-colors duration-150">
                        <div class="item-icon text-blue-500 pt-1">
                            <i data-lucide="bell-ring" class="w-5 h-5"></i>
                        </div>
                        <div class="item-content">
                            <p class="text-sm text-gray-700 leading-snug">
                                {{-- Asumsi data notifikasi punya 'message' --}}
                                {{ $notification->data['message'] ?? 'Anda memiliki notifikasi baru.' }}
                            </p>
                            <small class="text-gray-500 text-xs">{{ $notification->created_at->diffForHumans() }}</small>
                        </div>
                    </a>
                    @empty
                    <div class="p-4 text-center">
                        <p class="text-sm text-gray-500">Tidak ada notifikasi baru.</p>
                    </div>
                    @endforelse
                </div>

                <div class="dropdown-footer p-2 bg-gray-50 border-t border-gray-200">
                    <a href="#" class="block w-full text-center text-sm font-medium text-blue-600 hover:underline py-1">
                        Tandai semua dibaca
                    </a>
                </div>
            </div>
        </div>

        <div x-data="{ open: false }" @click.outside="open = false" class="relative">
            <button @click="open = !open" class="flex items-center gap-2 rounded-lg p-1 hover:bg-gray-100 transition-colors duration-200">
                <div class="w-9 h-9 bg-purple-500 text-white flex items-center justify-center rounded-full font-semibold text-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="text-left hidden md:block">
                    <span class="block text-sm font-medium text-gray-800">{{ Auth::user()->name }}</span>
                    <span class="block text-xs text-gray-500">{{ Auth::user()->getRoleNames()->first() ?? 'Pengguna' }}</span>
                </div>
                <i data-lucide="chevron-down" class="text-gray-500 w-4 h-4 hidden md:block" :class="{'rotate-180': open, 'rotate-0': !open}" style="transition: transform 0.2s;"></i>
            </button>

            <div x-show="open"
                x-transition:enter="transition ease-out duration-200"
                x-transition:enter-start="transform opacity-0 scale-95"
                x-transition:enter-end="transform opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-75"
                x-transition:leave-start="transform opacity-100 scale-100"
                x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-xl border border-gray-200 py-1"
                style="display: none; z-index: 1000;">

                <a href="{{ route('profile.edit') }}" class="flex items-center gap-2 px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors duration-150">
                    <i data-lucide="user" class="w-4 h-4"></i>
                    <span>Profil Saya</span>
                </a>

                <hr class="border-gray-200 my-1">

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50 transition-colors duration-150">
                        <i data-lucide="log-out" class="w-4 h-4"></i>
                        <span>Logout</span>
                    </button>
                </form>
            </div>
        </div>

    </div>
</nav>

{{-- Script untuk render ikon Lucide (seharusnya sudah ada di app.blade.php) --}}
@pushOnce('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        lucide.createIcons();
    });

    // Jika lucide di-render ulang (misal oleh Livewire, dll), panggil lagi:
    // document.addEventListener('livewire:load', function () {
    //     lucide.createIcons();
    // });
</script>
@endpushOnce