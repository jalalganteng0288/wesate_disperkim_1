<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- My Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />

    {{-- Leaflet CSS for Maps --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
</head>

<body class="font-sans antialiased bg-gray-100">
    <div class="d-flex" style="min-height: 100vh;">
        @include('layouts.sidebar')

        <div class="flex-grow-1">
            @include('layouts.topbar')

            <main>
                {{ $slot }}
            </main>
        </div>
    </div>

    {{-- Chart.js --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    {{-- Leaflet JS for Maps --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cek jika ada elemen notifikasi sebelum menjalankan script
            const notificationItems = document.querySelectorAll('.notification-item');
            if (notificationItems.length > 0) {
                notificationItems.forEach(item => {
                    item.addEventListener('click', function(e) {
                        e.preventDefault(); // Mencegah link langsung pindah halaman

                        const notificationId = this.dataset.id;
                        const targetUrl = this.href;

                        // Kirim request untuk menandai sudah dibaca
                        axios.post(`/admin/notifications/${notificationId}/read`, {
                                _token: '{{ csrf_token() }}'
                            })
                            .then(response => {
                                console.log('Notifikasi ditandai sudah dibaca');
                                // Setelah berhasil, lanjutkan ke halaman tujuan
                                window.location.href = targetUrl;
                            })
                            .catch(error => {
                                console.error('Gagal menandai notifikasi', error);
                                // Jika gagal, tetap lanjutkan ke halaman tujuan agar user tidak bingung
                                window.location.href = targetUrl;
                            });
                    });
                });
            }
        });
    </script>

    {{-- Ini adalah tempat untuk script dari halaman lain (seperti dashboard.blade.php) --}}
    @stack('scripts')
</body>

</html>