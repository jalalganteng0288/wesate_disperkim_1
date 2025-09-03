<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Media Library') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    {{ session('success') }}
                </div>
            @endif
            @if ($errors->any())
                <div class="bg-red-100 border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 border-b pb-2">Upload File Baru</h3>
                    <form action="{{ route('admin.media.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div>
                            <x-input-label for="file" :value="__('Pilih File (Max: 5MB)')" />
                            <input type="file" name="file" id="file" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>
                        <div class="flex items-center justify-end mt-4">
                            <x-primary-button>{{ __('Upload') }}</x-primary-button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="font-semibold text-lg mb-4 border-b pb-2">Daftar Media</h3>
                    @if($mediaItems->isEmpty())
                        <p class="text-center text-gray-500">Belum ada media yang di-upload.</p>
                    @else
                        <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                            @foreach ($mediaItems as $media)
                                <div class="border rounded-lg overflow-hidden group relative">
                                    @if(Str::startsWith($media->mime_type, 'image/'))
                                        <img src="{{ asset('storage/' . $media->file_path) }}" alt="{{ $media->file_name }}" class="w-full h-32 object-cover">
                                    @else
                                        <div class="w-full h-32 bg-gray-100 flex items-center justify-center">
                                            <span class="text-gray-500 text-xs text-center p-1">{{ Str::limit($media->file_name, 20) }}</span>
                                        </div>
                                    @endif
                                    <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-50 text-white p-1 text-xs opacity-0 group-hover:opacity-100 transition-opacity">
                                        <p class="truncate">{{ $media->file_name }}</p>
                                        <form action="{{ route('admin.media.destroy', $media->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus file ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-400 hover:text-red-600 text-xs">Hapus</button>
                                        </form>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <div class="mt-6">
                            {{ $mediaItems->links() }}
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>
</x-app-layout>