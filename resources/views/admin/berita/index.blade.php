<x-app-layout>
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Manajemen Berita</h1>
            <a href="{{ route('admin.berita.create') }}" class="btn btn-primary">Tulis Berita Baru</a>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Judul</th>
                            <th scope="col">Penulis</th>
                            <th scope="col">Status</th>
                            <th scope="col">Tanggal Publikasi</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($beritas as $key => $berita)
                        <tr>
                            <th scope="row">{{ $beritas->firstItem() + $key }}</th>
                            <td>{{ $berita->judul }}</td>
                            <td>{{ $berita->user->name }}</td>
                            <td>
                                @if($berita->status == 'published')
                                    <span class="badge bg-success">{{ ucfirst($berita->status) }}</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($berita->status) }}</span>
                                @endif
                            </td>
                            <td>{{ $berita->published_at ? $berita->published_at->format('d M Y') : 'Belum dipublikasi' }}</td>
                            <td>
                                <a href="#" class="btn btn-sm btn-info">Lihat</a>
                                <a href="{{ route('admin.berita.edit', $berita->id) }}" class="btn btn-sm btn-warning">Edit</a>
                                <form action="{{ route('admin.berita.destroy', $berita->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus berita ini?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Hapus</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada berita.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $beritas->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>