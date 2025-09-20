<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Statistik Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Ini adalah kode untuk membuat TABS (menggunakan Alpine.js) --}}
            <div x-data="{ tab: 'bulanan' }">
                
                {{-- Ini adalah tombol-tombol Tab --}}
                <div class="mb-4 border-b border-gray-200">
                    <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                        <button
                            @click="tab = 'bulanan'"
                            :class="tab === 'bulanan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Trend Bulanan
                        </button>
                        
                        <button
                            @click="tab = 'kecamatan'"
                            :class="tab === 'kecamatan' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Per Kecamatan
                        </button>
                        
                        <button
                            @click="tab = 'status'"
                            :class="tab === 'status' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                            Status
                        </button>
                    </nav>
                </div>

                {{-- Ini adalah KONTEN / ISI dari setiap Tab --}}
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        
                        {{-- Panel 1: Trend Bulanan (hanya tampil jika tab == 'bulanan') --}}
                        <div x-show="tab === 'bulanan'">
                            <h3 class="text-lg font-medium mb-4">Trend Pengaduan Bulanan (Tahun {{ date('Y') }})</h3>
                            <div style="height: 400px;">
                                <canvas id="chartBulanan"></canvas>
                            </div>
                        </div>

                        {{-- Panel 2: Per Kecamatan (hanya tampil jika tab == 'kecamatan') --}}
                        <div x-show="tab === 'kecamatan'" style="display: none;">
                            <h3 class="text-lg font-medium mb-4">Jumlah Pengaduan per Kecamatan</h3>
                            <div style="height: 400px;">
                                <canvas id="chartKecamatan"></canvas>
                            </div>
                        </div>

                        {{-- Panel 3: Status (hanya tampil jika tab == 'status') --}}
                        <div x-show="tab === 'status'" style="display: none;">
                            <h3 class="text-lg font-medium mb-4">Jumlah Pengaduan Berdasarkan Status</h3>
                            <div style="height: 400px;">
                                <canvas id="chartStatus"></canvas>
                            </div>
                        </div>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </div>

    {{-- Ini adalah kode JAVASCRIPT untuk menggambar GRAFIK --}}
    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Kita ambil data yang sudah disiapkan Controller
             const dataBulanan = JSON.parse('@json($dataBulanan)');
            const dataKecamatan = JSON.parse('@json($dataKecamatan)');
            const dataStatus = JSON.parse('@json($dataStatus)');
            // 1. Gambar Grafik Trend Bulanan
            const ctxBulanan = document.getElementById('chartBulanan').getContext('2d');
            new Chart(ctxBulanan, {
                type: 'bar',
                data: {
                    labels: dataBulanan.labels,
                    datasets: [{
                        label: 'Jumlah Pengaduan',
                        data: dataBulanan.data,
                        backgroundColor: 'rgba(59, 130, 246, 0.5)',
                        borderColor: 'rgba(59, 130, 246, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });

            // 2. Gambar Grafik Per Kecamatan
            const ctxKecamatan = document.getElementById('chartKecamatan').getContext('2d');
            new Chart(ctxKecamatan, {
                type: 'bar',
                data: {
                    labels: dataKecamatan.labels,
                    datasets: [{
                        label: 'Jumlah Pengaduan',
                        data: dataKecamatan.data,
                        backgroundColor: 'rgba(16, 185, 129, 0.5)',
                        borderColor: 'rgba(16, 185, 129, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { y: { beginAtZero: true } },
                    plugins: { legend: { display: false } }
                }
            });

            // 3. Gambar Grafik Status
            const ctxStatus = document.getElementById('chartStatus').getContext('2d');
            new Chart(ctxStatus, {
                type: 'doughnut',
                data: {
                    labels: dataStatus.labels,
                    datasets: [{
                        label: 'Jumlah Pengaduan',
                        data: dataStatus.data,
                        backgroundColor: [
                            'rgba(59, 130, 246, 0.7)', // Biru
                            'rgba(245, 158, 11, 0.7)', // Kuning
                            'rgba(16, 185, 129, 0.7)', // Hijau
                            'rgba(239, 68, 68, 0.7)'  // Merah
                        ],
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                }
            });
        });
    </script>
    @endpush
</x-app-layout>