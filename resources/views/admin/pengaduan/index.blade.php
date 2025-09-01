<x-app-layout>
    <div class="container-fluid">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manajemen Pengaduan</h1>
            <a href="{{ route('admin.pengaduan.create') }}" class="btn btn-primary">Buat Pengaduan Baru</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Pelapor</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pengaduans as $key => $pengaduan)
                        <tr>
                            <th scope="row">{{ $pengaduans->firstItem() + $key }}</th>
                            <td>{{ $pengaduan->judul }}</td>
                            <td>{{ $pengaduan->user->name }}</td>
                            <td>
                                <span class="badge bg-info text-dark">{{ ucfirst($pengaduan->status) }}</span>
                            </td>
                            <td>{{ $pengaduan->created_at->format('d M Y, H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.pengaduan.show', $pengaduan->id) }}" class="btn btn-sm btn-info">Detail</a>
                                <a href="{{ route('admin.pengaduan.edit', $pengaduan->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.pengaduan.destroy', $pengaduan->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data pengaduan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $pengaduans->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>