<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title @if(isset($page)) inertia @endif>{{ config('app.name', 'Laravel') }}</title>

  {{-- Muat CSS utama untuk SEMUA halaman (Blade & Inertia) --}}
  @vite(['resources/css/app.css'])

  {{-- HANYA muat aset Inertia/React JIKA ini halaman Inertia --}}
  @if (isset($page))
  @viteReactRefresh
  @vite(['resources/js/app.jsx'])
  @inertiaHead
  @endif

  {{-- lainnya --}}
  @stack('styles')
</head>

<body class="font-sans antialiased bg-gray-100 overflow-hidden">

    <div class="d-flex" style="height: 100vh;">
        
        @include('layouts.sidebar')

        <div class="flex-grow-1" style="display: flex; flex-direction: column; height: 100vh;">
            
            @include('layouts.topbar')

            <div class="flex-grow-1 overflow-y-auto p-6">
                <main>
                    {{-- Jika ini adalah page Inertia... --}}
                    @if (isset($page))
                        @inertia
                    @else
                    {{-- Untuk blade tradisional --}}
                    {{ $slot ?? '' }}
                    @endif
                </main>
            </div>
            
        </div>
    </div>

    {{-- script eksternal --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    {{-- inline script (tidak berubah) --}}
    <script>
      document.addEventListener('DOMContentLoaded', function() {
        if (typeof axios === 'undefined') {
          console.warn('Axios belum tersedia. Pastikan bootstrap.js diimpor di resources/js/app.jsx');
          return;
        }
        const notificationItems = document.querySelectorAll('.notification-item');
        notificationItems.forEach(item => {
          item.addEventListener('click', function(e) {
            e.preventDefault();
            const notificationId = this.dataset.id;
            const targetUrl = this.href;
            axios.post(`/admin/notifications/${notificationId}/read`, {
              _token: '{{ csrf_token() }}'
            }).finally(() => window.location.href = targetUrl);
          });
        });
      });
    </script>

    @stack('scripts')
</body>

</html>