<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Detail Pengaduan') }}
            </h2>
            <div>
                <a href="{{ route('admin.pengaduan.edit', $pengaduan->id) }}" class="inline-flex items-center px-4 py-2 bg-yellow-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-400">
                    Edit
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="border-b pb-4 mb-4">
                        <h3 class="text-lg font-bold text-gray-800">{{ $pengaduan->judul }}</h3>
                        <p class="text-sm text-gray-500">Dibuat pada: {{ $pengaduan->created_at->format('d F Y, H:i') }} WIB</p>
                    </div>

                    <div class="mb-6">
                        <strong class="block text-gray-600 font-bold mb-1">Isi Laporan:</strong>
                        <p class="mt-1 whitespace-pre-wrap text-gray-700 leading-relaxed">{{ $pengaduan->isi_laporan }}</p>
                    </div>

                    @if ($pengaduan->attachment_path)
                        <div class="mt-4">
                            <strong class="block text-gray-600 font-bold mb-2">Lampiran:</strong>
                            <img src="{{ asset('storage/' . $pengaduan->attachment_path) }}" alt="Lampiran Pengaduan" class="mt-2 rounded-lg border max-w-full h-auto">
                        </div>
                    @endif
                </div>
            </div>

            <div class="md:col-span-1 space-y-6">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4 border-b pb-2">Informasi Laporan</h3>
                        <div class="space-y-3 text-sm">
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Pelapor:</span>
                                <span class="text-gray-800">{{ $pengaduan->user->name ?? 'Anonim' }}</span>
                            </div>
                            @if($pengaduan->housingUnit)
                            <div class="flex justify-between">
                                <span class="font-semibold text-gray-600">Lokasi:</span>
                                <span class="text-gray-800">{{ $pengaduan->housingUnit->name }}</span>
                            </div>
                            @endif
                            <div class="flex justify-between items-center">
                                <span class="font-semibold text-gray-600">Status:</span>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    @if($pengaduan->status == 'baru') bg-blue-100 text-blue-800 
                                    @elseif($pengaduan->status == 'pengerjaan') bg-yellow-100 text-yellow-800 
                                    @elseif($pengaduan->status == 'selesai') bg-green-100 text-green-800 
                                    @else bg-red-100 text-red-800 @endif">
                                    {{ ucfirst($pengaduan->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Update Status</h3>

                        @if (session('success'))
                            <div class="bg-green-100 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.pengaduan.updateStatus', $pengaduan->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700 sr-only">Ubah Status</label>
                                <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                    <option value="baru" @selected($pengaduan->status == 'baru')>Baru</option>
                                    <option value="pengerjaan" @selected($pengaduan->status == 'pengerjaan')>Pengerjaan</option>
                                    <option value="selesai" @selected($pengaduan->status == 'selesai')>Selesai</option>
                                    <option value="ditolak" @selected($pengaduan->status == 'ditolak')>Ditolak</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <x-primary-button>{{ __('Update') }}</x-primary-button>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="{{ route('admin.pengaduan.index') }}" class="inline-block mt-2 text-sm text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Daftar Pengaduan
                </a>
            </div>

        </div>
    </div>
</x-app-layout>