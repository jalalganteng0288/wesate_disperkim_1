<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <h2 class="font-bold text-2xl text-gray-800 leading-tight">
                {{ __('Data Kecamatan') }}
            </h2>
            <div class="flex items-center gap-2">
                <a href="#" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 disabled:opacity-25 transition ease-in-out duration-150">
                    <i data-lucide="download" class="w-4 h-4 mr-2"></i>
                    Export Data
                </a>
                <x-primary-button>
                    <i data-lucide="refresh-cw" class="w-4 h-4 mr-2"></i>
                    Update Data
                </x-primary-button>
            </div>
        </div>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <p class="text-gray-600 mb-6">Kelola data perumahan dan demografis setiap kecamatan di Kabupaten Garut.</p>

                {{-- 4 Kartu KPI (DINAMIS) --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">

                    <div class="border border-gray-200 rounded-lg p-5 flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Kecamatan</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalKecamatan }}</p>
                            <p class="text-xs text-gray-500 mt-2">Semua kecamatan aktif</p>
                        </div>
                        <i data-lucide="map-pin" class="w-7 h-7 text-gray-400"></i>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-5 flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Populasi</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalPopulasi, 0, ',', '.') }}</p>
                            <p class="text-xs text-green-600 mt-2">Data Disdukcapil</p>
                        </div>
                        <i data-lucide="users" class="w-7 h-7 text-gray-400"></i>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-5 flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Total Rumah</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalRumah, 0, ',', '.') }}</p>
                            <p class="text-xs text-green-600 mt-2">Data Perkim</p>
                        </div>
                        <i data-lucide="home" class="w-7 h-7 text-gray-400"></i>
                    </div>

                    <div class="border border-gray-200 rounded-lg p-5 flex items-start justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-500 uppercase">Rata-rata Rutilahu</p>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($avgRutilahuPercentage, 2) }}%</p>
                            <p class="text-xs text-red-600 mt-2">Dari total rumah</p>
                        </div>
                        <i data-lucide="alert-triangle" class="w-7 h-7 text-gray-400"></i>
                    </div>

                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <div class="relative w-full max-w-md">
                        <i data-lucide="search" class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400"></i>
                        <input type="text" placeholder="Cari Kecamatan..." class="pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg w-full text-sm">
                    </div>
                    <div class="flex items-center gap-2">
                        <label for="sort" class="text-sm text-gray-600">Urutkan:</label>
                        <select id="sort" class="border-gray-300 rounded-lg text-sm focus:ring-blue-500 focus:border-blue-500">
                            <option value="nama-az">Nama A-Z</option>
                            <option value="nama-za">Nama Z-A</option>
                            <option value="populasi-terbanyak">Populasi Terbanyak</option>
                        </select>
                    </div>
                </div>

                {{-- Wrapper Kartu Kecamatan (DINAMIS DARI LOOP) --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

                    @forelse ($kecamatans as $kecamatan)
                        @php
                            // Hitung persentase
                            $persentaseRutilahu = ($kecamatan->total_rumah > 0) ? ($kecamatan->total_rutilahu / $kecamatan->total_rumah) * 100 : 0;
                            $persentasePenyelesaianPengaduan = ($kecamatan->pengaduans_count > 0) ? ($kecamatan->pengaduan_selesai_count / $kecamatan->pengaduans_count) * 100 : 0;
                            $persentaseRumahBaik = 100 - $persentaseRutilahu;
                        @endphp

                        <div class="border border-gray-200 rounded-lg p-5 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <h3 class="text-xl font-bold text-gray-800">{{ $kecamatan->name }}</h3>
                                    <p class="text-sm text-gray-500">{{ $kecamatan->total_desa }} desa/kelurahan • {{ $kecamatan->luas_wilayah_km2 }} km²</p>
                                </div>
                                <span class="inline-flex items-center px-3 py-0.5 rounded-full text-sm font-medium bg-blue-100 text-blue-800">{{ $kecamatan->status }}</span>
                            </div>

                            <div class="grid grid-cols-2 gap-x-4 gap-y-2 mb-4">
                                <div class="text-sm"><span class="text-gray-500">Populasi:</span> <strong class="text-gray-800 float-right">{{ number_format($kecamatan->populasi) }}</strong></div>
                                <div class="text-sm"><span class="text-gray-500">Total Rumah:</span> <strong class="text-gray-800 float-right">{{ number_format($kecamatan->total_rumah) }}</strong></div>
                                <div class="text-sm"><span class="text-gray-500">Rutilahu:</span> <strong class="text-red-600 float-right">{{ number_format($kecamatan->total_rutilahu) }} ({{ number_format($persentaseRutilahu, 1) }}%)</strong></div>
                                <div class="text-sm"><span class="text-gray-500">Pengaduan:</span> <strong class="text-gray-800 float-right">{{ $kecamatan->pengaduans_count }} ({{ $kecamatan->pengaduan_selesai_count }} selesai)</strong></div>
                            </div>

                            <div class="space-y-3">
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-xs font-medium text-gray-600">Tingkat Penyelesaian Pengaduan</span>
                                        <span class="text-xs font-medium text-gray-600">{{ number_format($persentasePenyelesaianPengaduan, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-blue-600 h-1.5 rounded-full" style="width: {{ $persentasePenyelesaianPengaduan }}%"></div></div>
                                </div>
                                <div>
                                    <div class="flex justify-between mb-1">
                                        <span class="text-xs font-medium text-gray-600">Kondisi Rumah Baik</span>
                                        <span class="text-xs font-medium text-gray-600">{{ number_format($persentaseRumahBaik, 0) }}%</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-1.5"><div class="bg-green-500 h-1.5 rounded-full" style="width: {{ $persentaseRumahBaik }}%"></div></div>
                                </div>
                            </div>

                            <hr class="my-4">
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-400">Update terakhir: {{ $kecamatan->updated_at->format('d/m/Y') }}</span>
                                <div class="space-x-3">
                                    <a href="#" class="text-sm font-medium text-gray-500 hover:text-gray-800">Detail</a>
                                    <a href="#" class="text-sm font-medium text-blue-600 hover:text-blue-800">Edit</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500 col-span-2">Data kecamatan belum diisi. Silakan isi data master kecamatan terlebih dahulu.</p>
                    @endforelse

                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>
    @endpush
</x-app-layout>