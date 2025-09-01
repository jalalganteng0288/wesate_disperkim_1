<x-app-layout>
<div class="container-fluid">
<h1>Edit Pengaduan</h1>

    <div class="card mt-4 shadow">
        <div class="card-body">
            <form action="{{ route('admin.pengaduan.update', $pengaduan->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Pengaduan</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $pengaduan->judul) }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="isi_laporan" class="form-label">Isi Laporan</label>
                    <textarea class="form-control @error('isi_laporan') is-invalid @enderror" id="isi_laporan" name="isi_laporan" rows="5" required>{{ old('isi_laporan', $pengaduan->isi_laporan) }}</textarea>
                    @error('isi_laporan')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select @error('status') is-invalid @enderror" id="status" name="status" required>
                        @foreach ($statuses as $status)
                            <option value="{{ $status }}" {{ old('status', $pengaduan->status) == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.pengaduan.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

</x-app-layout>