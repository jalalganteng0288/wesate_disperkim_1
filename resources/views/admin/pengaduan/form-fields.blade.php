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
    <x-input-label for="judul" :value="__('Judul Pengaduan')" />
    <x-text-input id="judul" class="block mt-1 w-full" type="text" name="judul" :value="old('judul', $pengaduan->judul ?? '')" required placeholder="Contoh: Lampu jalan mati di depan Griya Intan Asri" />
    <p class="mt-1 text-sm text-gray-500">Berikan judul yang jelas dan singkat.</p>
</div>

<div class="mt-4">
    <x-input-label for="housing_unit_id" :value="__('Lokasi Perumahan (Jika terkait)')" />
    <select name="housing_unit_id" id="housing_unit_id" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="">-- Tidak terkait perumahan spesifik --</option>
        @foreach ($housingUnits as $unit)
            <option value="{{ $unit->id }}" @selected(old('housing_unit_id', $pengaduan->housing_unit_id ?? '') == $unit->id)>
                {{ $unit->name }}
            </option>
        @endforeach
    </select>
    <p class="mt-1 text-sm text-gray-500">Pilih perumahan jika lokasi pengaduan berada di dalam atau sekitar area perumahan tersebut.</p>
</div>

<div class="mt-4">
    <x-input-label for="isi_laporan" :value="__('Isi Laporan Lengkap')" />
    <textarea id="isi_laporan" name="isi_laporan" rows="6" class="block mt-1 w-full rounded-md shadow-sm border-gray-300" placeholder="Jelaskan detail masalah di sini...">{{ old('isi', $pengaduan->isi ?? '') }}</textarea>
    <p class="mt-1 text-sm text-gray-500">Jelaskan masalah secara rinci, termasuk lokasi spesifik (nama jalan, blok, nomor rumah jika ada).</p>
</div>