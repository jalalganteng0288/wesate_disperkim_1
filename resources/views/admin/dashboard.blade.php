<x-app-layout>
    <div class="container-fluid p-6">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Dashboard</h1>

        {{-- KPI Cards (modern & responsive) --}}
        <style>
    /* Basic CSS fallback styles (plain CSS, avoid Tailwind @apply so editor tidak eror) */
    .kpi-card-v2 {
        background: #ffffff;
        border-radius: 0.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.06), 0 1px 2px rgba(0,0,0,0.02);
        padding: 1rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }
    .kpi-icon {
        width: 3rem;
        height: 3rem;
        border-radius: 9999px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.125rem;
    }
    .activity-item { display:flex; align-items:flex-start; gap:0.75rem; margin-bottom:1rem; }
    .activity-icon { width:2.5rem; height:2.5rem; border-radius:9999px; display:flex; align-items:center; justify-content:center; background:#f3f4f6; color:#4b5563; }
</style>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <div class="kpi-card-v2">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Proyek Infrastruktur</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $proyekInfrastrukturCount }}</p>
                </div>
                <div class="kpi-icon bg-blue-100 text-blue-500"><i class="fa-solid fa-building"></i></div>
            </div>

            <div class="kpi-card-v2">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Total Perumahan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPerumahanCount }}</p>
                </div>
                <div class="kpi-icon bg-green-100 text-green-500"><i class="fa-solid fa-home"></i></div>
            </div>

            <div class="kpi-card-v2">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Total Pengguna</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPenggunaCount }}</p>
                </div>
                <div class="kpi-icon bg-orange-100 text-orange-500"><i class="fa-solid fa-users"></i></div>
            </div>

            <div class="kpi-card-v2">
                <div class="flex-1">
                    <p class="text-sm font-medium text-gray-500">Total Pengaduan</p>
                    <p class="text-3xl font-bold text-gray-800">{{ $totalPengaduanCount }}</p>
                </div>
                <div class="kpi-icon bg-purple-100 text-purple-500"><i class="fa-solid fa-list-check"></i></div>
            </div>
        </div>

        {{-- Legacy KPI row kept (non-breaking change) --}}
        <div class="row mb-4">
            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Pengaduan Baru</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengaduanBaruCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Dalam Pengerjaan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengaduanDikerjakanCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-4 col-md-6 mb-4">
                <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Selesai</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $pengaduanSelesaiCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart + Activity --}}
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="bg-white p-4 rounded-lg shadow-sm h-full">
                    <h3 class="font-bold text-gray-800 mb-4">Tren Pengaduan</h3>
                    <div style="height: 320px;">
                        {{-- data-* attribute menyimpan JSON sehingga tidak memicu parser JS di editor --}}
                        <canvas id="complaintChart"
                                data-labels='@json($chartLabels)'
                                data-data='@json($chartData)'
                                role="img"
                                aria-label="Grafik tren pengaduan"></canvas>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mb-4">
                <div class="bg-white p-4 rounded-lg shadow-sm h-full">
                    <h3 class="font-bold text-gray-800 mb-4">Aktivitas Terbaru</h3>
                    <div class="activity-feed">
                        @forelse ($aktivitasTerbaru as $log)
                            <div class="activity-item">
                                <div class="activity-icon">
                                    <i class="fa-solid fa-bell"></i>
                                </div>
                                <div class="activity-content">
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
    </div>

    {{-- Scripts: menggunakan data-* parsing agar aman di editor & runtime --}}
    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Pastikan Chart.js sudah dimuat (via CDN atau bundle) sebelum script ini
        if (typeof Chart === 'undefined') {
            console.warn('Chart.js belum dimuat. Pastikan menambahkan script Chart.js (mis. https://cdn.jsdelivr.net/npm/chart.js) di layout.');
            return;
        }

        const canvas = document.getElementById('complaintChart');
        if (!(canvas instanceof HTMLCanvasElement)) return;

        // Ambil data dari attribute (string) lalu parse ke object/array
        let labels = [];
        let data = [];
        try {
            labels = JSON.parse(canvas.dataset.labels || '[]');
        } catch (e) {
            console.error('Gagal parse chartLabels:', e);
        }
        try {
            data = JSON.parse(canvas.dataset.data || '[]');
        } catch (e) {
            console.error('Gagal parse chartData:', e);
        }

        const ctx = canvas.getContext('2d');
        if (!ctx) return;

        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(78, 115, 223, 0.28)');
        gradient.addColorStop(1, 'rgba(78, 115, 223, 0)');

        // Create chart instance (non-destructive to backend logic)
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
                animation: { duration: 0 },
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
