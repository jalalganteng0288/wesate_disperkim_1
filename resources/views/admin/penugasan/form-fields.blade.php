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
    <x-input-label for="title" :value="__('Judul Tugas / Pekerjaan')" />
    <x-text-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $penugasan->title ?? '')" required />
</div>

<div class="mt-4">
    <x-input-label for="complaint_id" :value="__('Terkait Pengaduan')" />
    <select name="complaint_id" id="complaint_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- Pilih Pengaduan --</option>
        @foreach ($pengaduans as $pengaduan)
            <option value="{{ $pengaduan->id }}" @selected(old('complaint_id', $penugasan->complaint_id ?? '') == $pengaduan->id)>
                {{ Str::limit($pengaduan->judul, 80) }}
            </option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <x-input-label for="assignee_id" :value="__('Tugaskan Kepada Pegawai')" />
    <select name="assignee_id" id="assignee_id" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
        <option value="">-- Pilih Pegawai --</option>
        @foreach ($assignees as $assignee)
            <option value="{{ $assignee->id }}" @selected(old('assignee_id', $penugasan->assignee_id ?? '') == $assignee->id)>
                {{ $assignee->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mt-4">
    <x-input-label for="description" :value="__('Deskripsi / Instruksi (Opsional)')" />
    <textarea id="description" name="description" rows="4" class="block mt-1 w-full rounded-md shadow-sm border-gray-300">{{ old('description', $penugasan->description ?? '') }}</textarea>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
    <div>
        <x-input-label for="status" :value="__('Status')" />
        <select name="status" id="status" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm">
            <option value="Pending" @selected(old('status', $penugasan->status ?? '') == 'Pending')>Pending</option>
            <option value="In Progress" @selected(old('status', $penugasan->status ?? '') == 'In Progress')>In Progress</option>
            <option value="Completed" @selected(old('status', $penugasan->status ?? '') == 'Completed')>Completed</option>
            <option value="Cancelled" @selected(old('status', $penugasan->status ?? '') == 'Cancelled')>Cancelled</option>
        </select>
    </div>
    <div>
        <x-input-label for="due_date" :value="__('Batas Waktu (Opsional)')" />
        <x-text-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', isset($penugasan) && $penugasan->due_date ? \Carbon\Carbon::parse($penugasan->due_date)->format('Y-m-d') : '')" />
    </div>
</div>