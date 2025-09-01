<x-app-layout>
<div class="container-fluid">
<h1>Edit Berita</h1>

    <div class="card mt-4 shadow">
        <div class="card-body">
            <form action="{{ route('admin.berita.update', $berita->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="judul" class="form-label">Judul Berita</label>
                    <input type="text" class="form-control @error('judul') is-invalid @enderror" id="judul" name="judul" value="{{ old('judul', $berita->judul) }}" required>
                    @error('judul')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="konten" class="form-label">Konten</label>
                    <textarea class="form-control @error('konten') is-invalid @enderror" id="konten" name="konten" rows="15">{{ old('konten', $berita->konten) }}</textarea>
                    @error('konten')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                
                <div class="mb-3">
                    <label for="status" class="form-label">Status</label>
                    <select class="form-select" id="status" name="status">
                        <option value="draft" {{ old('status', $berita->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                        <option value="published" {{ old('status', $berita->status) == 'published' ? 'selected' : '' }}>Publish</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.berita.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        tinymce.init({
            selector: 'textarea#konten',
            plugins: 'code table lists image link',
            toolbar: 'undo redo | blocks | bold italic | alignleft aligncenter alignright | indent outdent | bullist numlist | code | table | image | link'
        });
    </script>
@endpush

</x-app-layout>