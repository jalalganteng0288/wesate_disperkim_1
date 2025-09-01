<x-app-layout>
<div class="container-fluid">
<div class="d-flex justify-content-between align-items-center mb-4">
<h1>Detail Pengaduan</h1>
<div>
<a href="{{ route('admin.pengaduan.edit', $pengaduan->id) }}" class="btn btn-warning">Edit Pengaduan</a>
<a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary">Kembali ke Daftar</a>
</div>
</div>

    <div class="card shadow">
        <div class="card-header">
            <h4 class="mb-0">{{ $pengaduan->judul }}</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <h5>Isi Laporan:</h5>
                    <p style="white-space: pre-wrap;">{{ $pengaduan->isi_laporan }}</p>
                </div>
                <div class="col-md-4">
                    <div class="list-group">
                        <div class="list-group-item">
                            <strong>Status:</strong> 
                            <span class="badge bg-info text-dark">{{ ucfirst($pengaduan->status) }}</span>
                        </div>
                        <div class="list-group-item">
                            <strong>Pelapor:</strong> {{ $pengaduan->user->name }}
                        </div>
                        <div class="list-group-item">
                            <strong>Email Pelapor:</strong> {{ $pengaduan->user->email }}
                        </div>
                        <div class="list-group-item">
                            <strong>Tanggal Dibuat:</strong> {{ $pengaduan->created_at->format('d M Y, H:i') }}
                        </div>
                        <div class="list-group-item">
                            <strong>Terakhir Diperbarui:</strong> {{ $pengaduan->updated_at->format('d M Y, H:i') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</x-app-layout>