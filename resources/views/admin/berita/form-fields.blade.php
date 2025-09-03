@if ($errors->any())
    <div class="alert alert-danger mb-4">
        <ul class="list-disc list-inside text-sm text-red-600">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <x-input-label for="judul" :value="__('Judul Berita')" />
    <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul', $berita->judul ?? '')" required autofocus />
</div>

<div class="mt-4">
    <x-input-label for="status" :value="__('Status')" />
    <select name="status" id="status" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="draft" @selected(old('status', $berita->status ?? '') == 'draft')>Draft</option>
        <option value="published" @selected(old('status', $berita->status ?? '') == 'published')>Published</option>
    </select>
</div>

<div class="mt-4">
    <x-input-label for="konten" :value="__('Konten Berita')" />
    {{-- Ini adalah textarea yang akan kita ubah menjadi editor --}}
    <textarea id="content-editor" name="konten" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('konten', $berita->konten ?? '') }}</textarea>
</div>

<div class="mt-4">
    <x-input-label for="image" :value="__('Gambar Utama (Opsional)')" />
    <input type="file" name="image" id="image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
     @if (isset($berita) && $berita->image_path)
         <div class="mt-2">
             <p class="text-sm text-gray-500">Gambar saat ini:</p>
             <img src="{{ asset('storage/' . $berita->image_path) }}" alt="{{ $berita->judul }}" class="h-40 w-auto object-cover mt-1 rounded">
         </div>
     @endif
</div>