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
    <x-input-label for="name" :value="__('Nama Perumahan')" />
    <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $perumahan->name ?? '')" required />
</div>

<div class="mt-4">
    <x-input-label for="developer_name" :value="__('Pengembang (Developer)')" />
    <x-text-input id="developer_name" class="block mt-1 w-full" type="text" name="developer_name" :value="old('developer_name', $perumahan->developer_name ?? '')" required />
</div>

<div class="mt-4">
    <x-input-label for="contact_person" :value="__('Narahubung (Contact Person)')" />
    <x-text-input id="contact_person" class="block mt-1 w-full" type="text" name="contact_person" :value="old('contact_person', $perumahan->contact_person ?? '')" required />
</div>

<div class="mt-4">
    <x-input-label for="unit_type" :value="__('Tipe Unit')" />
    <select name="unit_type" id="unit_type" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
        <option value="Subsidi" @selected(old('unit_type', $perumahan->unit_type ?? '') == 'Subsidi')>Subsidi</option>
        <option value="Komersil" @selected(old('unit_type', $perumahan->unit_type ?? '') == 'Komersil')>Komersil</option>
    </select>
</div>

<div class="mt-4">
    <x-input-label for="total_units" :value="__('Total Unit')" />
    <x-text-input id="total_units" class="block mt-1 w-full" type="number" name="total_units" :value="old('total_units', $perumahan->total_units ?? '')" required />
</div>

<div class="mt-4">
    <x-input-label for="address" :value="__('Alamat Lengkap')" />
    <textarea id="address" name="address" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('address', $perumahan->address ?? '') }}</textarea>
</div>