<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
<meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="stylesheet" href="{{ asset('css/admin_style.css') }}">

    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-gray-100">
            
            <div class="d-flex">
                @include('layouts.sidebar')

                <main class="main-content flex-grow-1">
                    {{-- Ini adalah "slot" utama dimana konten halaman akan dimasukkan --}}
                    {{ $slot }}
                </main>
            </div>
            
        </div>
        <script src="https://cdn.tiny.cloud/1/xfo1mgzbpdozo1ofx8w550fcyv3s2q5d4wwn23igp4fuszse/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        @stack('scripts')
    </body>
</html>