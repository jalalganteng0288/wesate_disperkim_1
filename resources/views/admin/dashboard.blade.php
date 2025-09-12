<x-app-layout>
    {{-- Slot untuk Judul Halaman --}}
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard
        </h1>
    </x-slot>

    {{-- Konten Utama Dashboard dengan Jarak Antar Elemen (space-y-6) --}}
    <div class="space-y-6">
        {{-- Baris Pertama: Kartu KPI (Key Performance Indicator) menggunakan Grid --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            {{-- Kartu Pengaduan Baru --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Pengaduan Baru</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pengaduanBaruCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                    <i class="fa-solid fa-inbox text-red-500"></i>
                </div>
            </div>

            {{-- Kartu Proyek Infrastruktur --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Proyek Infrastruktur</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $proyekInfrastrukturCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-blue-100 flex items-center justify-center">
                    <i class="fa-solid fa-building text-blue-500"></i>
                </div>
            </div>

            {{-- Kartu Total Perumahan --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Perumahan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPerumahanCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-green-100 flex items-center justify-center">
                    <i class="fa-solid fa-home text-green-500"></i>
                </div>
            </div>
            
            {{-- Kartu Total Pengguna --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPenggunaCount }}</p>
                </div>
                <div class="w-12 h-12 rounded-full bg-orange-100 flex items-center justify-center">
                    <i class="fa-solid fa-users text-orange-500"></i>
                </div>
            </div>
        </div>

        {{-- Baris Kedua: Grafik dan Aktivitas Terbaru menggunakan Grid --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Kolom Grafik (mengambil 2 dari 3 kolom di layar besar) --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Tren Pengaduan</h3>
                <div style="height: 320px;">
                    <canvas id="complaintChart"
                            data-labels='@json($chartLabels)'
                            data-data='@json($chartData)'
                            role="img"
                            aria-label="Grafik tren pengaduan"></canvas>
                </div>
            </div>

            {{-- Kolom Aktivitas --}}
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    @forelse ($aktivitasTerbaru as $log)
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-100 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                <i class="fa-solid fa-bell"></i>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700">
                                    @if(Str::contains($log->action, 'created'))
                                        <strong>{{ $log->resource_type }}</strong> baru dibuat oleh {{ $log->actor->name ?? 'Sistem' }}.
                                    @elseif(Str::contains($log->action, 'updated'))
                                        <strong>{{ $log->resource_type }}</strong> diperbarui oleh {{ $log->actor->name ?? 'Sistem' }}.
                                    @else
                                        Aktivitas: {{ $log->action }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">Belum ada aktivitas terbaru.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Chart.js (LOGIKA TIDAK DIUBAH) --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        if (typeof Chart === 'undefined') return;
        const canvas = document.getElementById('complaintChart');
        if (!canvas) return;

        let labels = JSON.parse(canvas.dataset.labels || '[]');
        let data = JSON.parse(canvas.dataset.data || '[]');
        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.28)');
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pengaduan Masuk',
                    backgroundColor: gradient,
                    borderColor: 'rgba(78, 115, 223, 1)',
                    data: data,
                    fill: true,
                    tension: 0.36,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    });
    </script>
    @endpush
</x-app-layout>

