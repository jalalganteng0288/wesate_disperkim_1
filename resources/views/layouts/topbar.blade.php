<header class="topbar">
    <div class="topbar-left">
        {{-- <button class="hamburger-btn">
            <i class="fa-solid fa-bars"></i>
        </button> --}}
        <div class="search-bar">
            <i class="fa-solid fa-search"></i>
            <input type="text" placeholder="Cari pengaduan, laporan...">
        </div>
    </div>
    <div class="topbar-right">
        <div class="notification-wrapper" x-data="{ open: false }">
            <button @click="open = !open" class="notification-btn">
                <i class="fa-regular fa-bell"></i>
                @if($notificationCount > 0)
                    <span class="notification-badge">{{ $notificationCount }}</span>
                @endif
            </button>

            <div x-show="open" @click.away="open = false" class="notification-dropdown" style="display: none;">
                <div class="dropdown-header">
                    <h6 class="font-bold">Notifikasi</h6>
                    @if($notificationCount > 0)
                        <a href="#" class="text-sm">Tandai semua sudah dibaca</a>
                    @endif
                </div>
                <div class="dropdown-body">
                    @forelse ($unreadNotifications as $notification)
                        <a href="{{ route('admin.pengaduan.show', $notification->data['complaint_id']) }}"
                           class="notification-item"
                           data-id="{{ $notification->id }}">
                            <div class="item-icon">
                                <i class="fa-solid fa-file-circle-plus text-blue-500"></i>
                            </div>
                            <div class="item-content">
                                <p>Pengaduan baru <strong>"{{ Str::limit($notification->data['title'], 25) }}"</strong> oleh {{ $notification->data['user_name'] }}.</p>
                                <small class="text-gray-500">{{ $notification->created_at->diffForHumans() }}</small>
                            </div>
                        </a>
                    @empty
                        <div class="text-center text-gray-500 p-4">
                            Tidak ada notifikasi baru.
                        </div>
                    @endforelse
                </div>
                <div class="dropdown-footer">
                    <a href="#">Lihat Semua Notifikasi</a>
                </div>
            </div>
        </div>

        <div class="hidden sm:flex sm:items-center sm:ms-3">
            <x-dropdown align="right" width="48">
                <x-slot name="trigger">
                    <button class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:text-gray-900 focus:outline-none transition ease-in-out duration-150">
                        @if(Auth::user()->profile_photo_path)
                            <img src="{{ Storage::url(Auth::user()->profile_photo_path) }}" alt="Photo" class="h-8 w-8 rounded-full object-cover me-2">
                        @endif
                        <div>{{ Auth::user()->name }}</div>
                        <div class="ms-1">
                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </button>
                </x-slot>

                <x-slot name="content">
                    <x-dropdown-link :href="route('admin.pengaturan.index') . '#profil'">
                        {{ __('Profil Saya') }}
                    </x-dropdown-link>
                    <x-dropdown-link :href="route('admin.pengaturan.index')">
                        {{ __('Pengaturan') }}
                    </x-dropdown-link>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                            {{ __('Log Out') }}
                        </x-dropdown-link>
                    </form>
                </x-slot>
            </x-dropdown>
        </div>
    </div>
</header>