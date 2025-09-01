<x-app-layout>
    {{-- Anda bisa menaruh judul halaman di sini jika mau --}}
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard Admin
        </h2>
    </x-slot> --}}

    {{-- BUNGKUS KONTEN LAMA ANDA DI SINI --}}
    <div class="container-fluid">
        <h1 class="mb-4">Dashboard Admin</h1>

        <div class="row">
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card kpi-card">
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
            <div class="col-xl-3 col-md-6 mb-4">
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
            <div class="col-xl-3 col-md-6 mb-4">
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
            <div class="col-xl-3 col-md-6 mb-4">
                <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                            <div class="col mr-2">
                                <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total Pengaduan</div>
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalPengaduanCount }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Tren Pengaduan Tahun {{ date('Y') }}</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="complaintChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">5 Pengaduan Terakhir</h6>
                    </div>
                    <div class="card-body">
                        @forelse ($pengaduanTerakhir as $pengaduan)
                        <div class="border-bottom mb-2 pb-2">
                            <a href="{{ route('admin.pengaduan.show', $pengaduan->id) }}" class="text-decoration-none">
                                <p class="fw-bold mb-0 text-dark">{{ Str::limit($pengaduan->judul, 35) }}</p>
                            </a>
                            <small>Oleh: {{ $pengaduan->user->name ?? 'N/A' }} - {{ $pengaduan->created_at->diffForHumans() }}</small>
                        </div>
                        @empty
                        <p>Belum ada pengaduan.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const labels = JSON.parse('{!! json_encode($chartLabels) !!}');
            const data = JSON.parse('{!! json_encode($chartData) !!}');
            const ctx = document.getElementById('complaintChart');
            if (ctx) {
                const complaintChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Pengaduan',
                            backgroundColor: 'rgba(78, 115, 223, 0.5)',
                            borderColor: 'rgba(78, 115, 223, 1)',
                            data: data,
                        }]
                    },
                    options: {
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            }
        });
    </script>
    @endpush
</x-app-layout>