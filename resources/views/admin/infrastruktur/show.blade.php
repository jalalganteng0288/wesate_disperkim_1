<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Detail Laporan Infrastruktur') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="md:col-span-2 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold border-b pb-2 mb-4">Laporan: {{ ucfirst($infrastructureReport->type) }}</h3>

                    <div class="mb-4">
                        <strong class="block text-gray-600">Pelapor:</strong>
                        <span>{{ $infrastructureReport->user->name ?? 'Anonim' }}</span>
                    </div>

                    <div class="mb-4">
                        <strong class="block text-gray-600">Tanggal Lapor:</strong>
                        <span>
                            @if($infrastructureReport->created_at)
                                {{ $infrastructureReport->created_at->format('d F Y, H:i') }} WIB
                            @else
                                (Tanggal tidak tersedia)
                            @endif
                        </span>
                    </div>

                    <div class="mb-4">
                        <strong class="block text-gray-600">Deskripsi:</strong>
                        <p class="mt-1 whitespace-pre-wrap">{{ $infrastructureReport->description }}</p>
                    </div>

                    @if ($infrastructureReport->photo_url)
                         <div class="mt-4">
                             <strong class="block text-gray-600">Lampiran:</strong>
                             <img src="{{ asset('storage/' . $infrastructureReport->photo_url) }}" alt="Lampiran Laporan" class="mt-2 rounded-lg border max-w-full h-auto">
                         </div>
                     @endif
                </div>
            </div>

            <div class="md:col-span-1">
                 <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <h3 class="text-lg font-bold mb-4">Workflow & Approval</h3>

                         @if (session('success'))
                            <div class="bg-green-100 text-green-700 px-4 py-3 rounded relative mb-4 text-sm" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.infrastruktur.updateStatus', $infrastructureReport->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div>
                                <label for="status" class="block font-medium text-sm text-gray-700">Ubah Status</label>
                                <select name="status" id="status" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">
                                    <option value="Baru" @selected($infrastructureReport->status == 'Baru')>Baru</option>
                                    <option value="Verifikasi" @selected($infrastructureReport->status == 'Verifikasi')>Verifikasi</option>
                                    <option value="Penjadwalan" @selected($infrastructureReport->status == 'Penjadwalan')>Penjadwalan</option>
                                    <option value="Pengerjaan" @selected($infrastructureReport->status == 'Pengerjaan')>Pengerjaan</option>
                                    <option value="Selesai" @selected($infrastructureReport->status == 'Selesai')>Selesai</option>
                                    <option value="Ditolak" @selected($infrastructureReport->status == 'Ditolak')>Ditolak</option>
                                </select>
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
                <a href="{{ route('admin.infrastruktur.index') }}" class="inline-block mt-4 text-sm text-indigo-600 hover:text-indigo-900">
                    &larr; Kembali ke Daftar Laporan
                </a>
            </div>

        </div>
    </div>
</x-app-layout>