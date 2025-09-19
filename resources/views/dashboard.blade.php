<x-app-layout>
    {{-- Slot Judul Halaman (Tidak Berubah) --}}
    <x-slot name="header">
        <h1 class="text-2xl font-bold text-gray-800">
            Dashboard
        </h1>
    </x-slot>

    {{-- Konten Utama Dashboard --}}
    <div class="space-y-6">

        {{-- =============================================== --}}
        {{-- === KARTU KPI (IKON DIPERBARUI) === --}}
        {{-- =============================================== --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">

            {{-- Kartu Pengaduan Baru (IKON BARU: Siren) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 50ms;">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Pengaduan Baru</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $pengaduanBaruCount }}</p>
                </div>
                {{-- Ini adalah ikon dari image_2ea055.png --}}
                <div class="w-12 h-12 rounded-md bg-red-100 flex items-center justify-center">
                    <i data-lucide="siren" class="text-red-500 w-6 h-6"></i>
                </div>
            </div>

            {{-- Kartu Proyek Infrastruktur (IKON BARU: Building-2) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 100ms;">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Proyek Infrastruktur</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $proyekInfrastrukturCount }}</p>
                </div>
                {{-- Ini adalah ikon dari image_2ea055.png --}}
                <div class="w-12 h-12 rounded-md bg-blue-100 flex items-center justify-center">
                    <i data-lucide="building-2" class="text-blue-500 w-6 h-6"></i>
                </div>
            </div>

            {{-- Kartu Total Perumahan (IKON BARU: Home) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 150ms;">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Perumahan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPerumahanCount }}</p>
                </div>
                 {{-- Ini adalah ikon dari image_2ea055.png --}}
                <div class="w-12 h-12 rounded-md bg-green-100 flex items-center justify-center">
                    <i data-lucide="home" class="text-green-500 w-6 h-6"></i>
                </div>
            </div>
            
            {{-- Kartu Total Pengguna (IKON BARU: Users) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm flex items-center justify-between transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1 animate-fade-in" style="animation-delay: 200ms;">
                <div>
                    <p class="text-sm font-medium text-gray-500 uppercase">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPenggunaCount }}</p>
                </div>
                 {{-- Ini adalah ikon dari image_2ea055.png --}}
                <div class="w-12 h-12 rounded-md bg-orange-100 flex items-center justify-center">
                    <i data-lucide="users" class="text-orange-500 w-6 h-6"></i>
                </div>
            </div>
        </div>

        {{-- =============================================== --}}
        {{-- === GRAFIK & AKTIVITAS (AKTIVITAS DIFUNGSIKAN) === --}}
        {{-- =============================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            
            {{-- Kolom Grafik (Tidak Berubah) --}}
            <div class="lg:col-span-2 bg-white p-6 rounded-lg shadow-sm animate-fade-in" style="animation-delay: 250ms;">
                <h3 class="font-bold text-gray-800 mb-4">Tren Pengaduan</h3>
                <div style="height: 320px;">
                    <canvas id="complaintChart"
                            data-labels='@json($chartLabels)'
                            data-data='@json($chartData)'
                            role="img"
                            aria-label="Grafik tren pengaduan"></canvas>
                </div>
            </div>

            {{-- Kolom Aktivitas (IKON DIPERBARUI & DIFUNGSIKAN) --}}
            <div class="bg-white p-6 rounded-lg shadow-sm animate-fade-in" style="animation-delay: 300ms;">
                <h3 class="font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                <div class="space-y-4">
                    
                    {{-- Loop ini mengambil data dari DashboardController --}}
                    @forelse ($aktivitasTerbaru as $log)
                        <div class="flex items-start gap-3">
                            {{-- LOGIKA BARU: Ganti ikon berdasarkan jenis aktivitas --}}
                            <div class="w-10 h-10 rounded-full bg-indigo-50 text-indigo-500 flex items-center justify-center flex-shrink-0">
                                @if($log->resource_type == 'Pengaduan')
                                    <i data-lucide="bell-ring" class="w-5 h-5"></i>
                                @elseif($log->resource_type == 'User')
                                    <i data-lucide="user-cog" class="w-5 h-5"></i>
                                @elseif($log->resource_type == 'News' || $log->resource_type == 'Berita')
                                    <i data-lucide="file-text" class="w-5 h-5"></i>
                                @else
                                    <i data-lucide="activity" class="w-5 h-5"></i>
                                @endif
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-700 leading-tight">
                                    {{-- Logika untuk menampilkan pesan (Tidak Berubah) --}}
                                    @if(Str::contains($log->action, 'created'))
                                        <strong>{{ $log->resource_type }}</strong> baru dibuat oleh {{ $log->actor->name ?? 'Sistem' }}.
                                    @elseif(Str::contains($log->action, 'updated'))
                                        <strong>{{ $log->resource_type }}</strong> diperbarui oleh {{ $log->actor->name ?? 'Sistem' }}.
                                    @else
                                        Aktivitas: {{ $log->action }}
                                    @endif
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $log->created_at->diffForHumans() }}</p>
                            </div>
                        </div>
                    @empty
                        {{-- Tampilan jika data $aktivitasTerbaru benar-benar kosong --}}
                        <div class="flex items-center gap-3 text-gray-400">
                            <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center flex-shrink-0">
                                <i data-lucide="check-check" class="w-5 h-5"></i>
                            </div>
                            <p class="text-sm">Belum ada aktivitas terbaru.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    {{-- Script untuk Chart.js (LOGIKA TIDAK DIUBAH) --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Render ikon Lucide baru yang kita tambahkan
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // Logika Chart (Sama seperti sebelumnya)
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