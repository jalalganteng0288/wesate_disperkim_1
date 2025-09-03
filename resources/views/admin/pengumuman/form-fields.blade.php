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
    <x-input-label for="title" :value="__('Judul Pengumuman')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $pengumuman->title ?? '')" required />
</div>

<div class="mt-4">
    <x-input-label for="content" :value="__('Isi Pengumuman')" />
    <textarea id="content" name="content" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('content', $pengumuman->content ?? '') }}</textarea>
</div>

<div class="mt-4">
    <x-input-label for="image" :value="__('Gambar (Opsional)')" />
    <input type="file" name="image" id="image" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
    <p class="mt-1 text-sm text-gray-500">Gambar ini akan ditampilkan pada banner atau popup.</p>
     @if (isset($pengumuman) && $pengumuman->image_path)
         <div class="mt-2">
             <p class="text-sm text-gray-500">Gambar saat ini:</p>
             <img src="{{ asset('storage/' . $pengumuman->image_path) }}" alt="{{ $pengumuman->title }}" class="h-40 w-auto object-cover mt-1 rounded">
         </div>
     @endif
</div>
<div class="mt-4">
    <x-input-label for="placement" :value="__('Penempatan')" />
    <select name="placement" id="placement" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        <option value="banner" @selected(old('placement', $pengumuman->placement ?? '') == 'banner')>Banner</option>
        <option value="popup" @selected(old('placement', $pengumuman->placement ?? '') == 'popup')>Popup</option>
    </select>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <x-input-label for="start_date" :value="__('Tanggal Mulai (Opsional)')" />
        <x-text-input id="start_date" class="block mt-1 w-full" type="date" name="start_date" :value="old('start_date', isset($pengumuman) && $pengumuman->start_date ? \Carbon\Carbon::parse($pengumuman->start_date)->format('Y-m-d') : '')" />
    </div>
    <div>
        <x-input-label for="end_date" :value="__('Tanggal Selesai (Opsional)')" />
        <x-text-input id="end_date" class="block mt-1 w-full" type="date" name="end_date" :value="old('end_date', isset($pengumuman) && $pengumuman->end_date ? \Carbon\Carbon::parse($pengumuman->end_date)->format('Y-m-d') : '')" />
    </div>
</div>

<div class="mt-4">
    <x-input-label for="is_published" :value="__('Status')" />
    <select name="is_published" id="is_published" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        <option value="1" @selected(old('is_published', $pengumuman->is_published ?? '1') == '1')>Published</option>
        <option value="0" @selected(old('is_published', $pengumuman->is_published ?? '') == '0')>Draft</option>
    </select>
</div>