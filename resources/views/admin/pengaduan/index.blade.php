<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pengaduan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-4 p-4 border rounded-lg bg-gray-50">
                        <form action="{{ route('admin.pengaduan.index') }}" method="GET">
                            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                                <div>
                                    <label for="search" class="block text-sm font-medium text-gray-700">Cari Judul</label>
                                    <input type="text" name="search" id="search" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('search') }}" placeholder="Kata kunci...">
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                    <select name="status" id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm">
                                        <option value="">Semua Status</option>
                                        <option value="baru" @selected(request('status') == 'baru')>Baru</option>
                                        <option value="pengerjaan" @selected(request('status') == 'pengerjaan')>Pengerjaan</option>
                                        <option value="selesai" @selected(request('status') == 'selesai')>Selesai</option>
                                        <option value="ditolak" @selected(request('status') == 'ditolak')>Ditolak</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                                    <input type="date" name="start_date" id="start_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('start_date') }}">
                                </div>
                                <div>
                                    <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                                    <input type="date" name="end_date" id="end_date" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm" value="{{ request('end_date') }}">
                                </div>
                            </div>
                            <div class="mt-4 flex justify-end space-x-2">
                                <a href="{{ route('admin.pengaduan.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-300 border rounded-md text-sm font-medium text-gray-700 hover:bg-gray-400">Reset</a>
                                <button type="submit" class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500">
                                    Filter
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="flex justify-between items-center mb-4">
                        <a href="{{ route('admin.pengaduan.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                            Buat Pengaduan
                        </a>
                        <div class="flex space-x-2">
                            <a href="{{ route('admin.reports.complaints.pdf') }}" target="_blank" class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500">
                                Export PDF
                            </a>
                            <a href="{{ route('admin.reports.complaints.csv') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500">
                                Export CSV
                            </a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            <span class="block sm:inline">{{ session('success') }}</span>
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            {{-- ... sisa kode tabel Anda (thead, tbody, dll) tidak perlu diubah ... --}}
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">No</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Pengaduan</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Pelapor</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Tanggal</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="relative px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($pengaduans as $key => $pengaduan)
                                    <tr>
                                        <td class="px-6 py-4">{{ $pengaduans->firstItem() + $key }}</td>
                                        <td class="px-6 py-4 font-medium">{{ $pengaduan->judul }}</td>
                                        <td class="px-6 py-4">{{ $pengaduan->user->name ?? 'Anonim' }}</td>
                                        <td class="px-6 py-4">{{ $pengaduan->created_at->format('d M Y') }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($pengaduan->status == 'baru') bg-blue-100 text-blue-800 
                                                @elseif($pengaduan->status == 'pengerjaan') bg-yellow-100 text-yellow-800 
                                                @elseif($pengaduan->status == 'selesai') bg-green-100 text-green-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($pengaduan->status) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.pengaduan.show', $pengaduan->id) }}" class="text-indigo-600 hover:text-indigo-900">Detail</a>
                                            <a href="{{ route('admin.pengaduan.edit', $pengaduan->id) }}" class="text-indigo-600 hover:text-indigo-900 ml-2">Edit</a>
                                            <form action="{{ route('admin.pengaduan.destroy', $pengaduan->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 hover:text-red-900 ml-2">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            Tidak ada data pengaduan yang cocok dengan filter.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $pengaduans->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>