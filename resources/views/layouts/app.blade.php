<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-g">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS (jika Anda masih menggunakannya di beberapa tempat) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- === [UNTUK PETA] Leaflet CSS === -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.css" />

    <!-- Scripts & Styles bawaan Laravel (Vite) -->
    {{-- Vite akan otomatis memasukkan app.css dan admin_style.css jika diimpor di app.js --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Slot untuk CSS tambahan per halaman -->
    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <div class="d-flex">
            @include('layouts.sidebar')

            <main class="main-content flex-grow-1">
                <!-- Page Heading -->
                @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
                @endif

                <!-- Page Content -->
                {{ $slot }}
            </main>
        </div>

    </div>

    <!-- === [UNTUK PETA] Leaflet JS === -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

    <!-- === [UNTUK GRAFIK] Chart.js === -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <!-- === [UNTUK EDITOR] TinyMCE (jika tidak dimuat per halaman) === -->
    {{-- <script src="https://cdn.tiny.cloud/1/xfo1mgzbpdozo1ofx8w550fcyv3s2q5d4wwn23igp4fuszse/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script> --}}
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script src="https://unpkg.com/leaflet-geosearch@3.11.0/dist/geosearch.umd.js"></script>

    <!-- Slot untuk JS tambahan per halaman -->
    @stack('scripts')
</body>

</html>