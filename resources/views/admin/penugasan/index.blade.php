<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Penugasan / Work Orders') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <a href="{{ route('admin.penugasan.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 mb-4">
                        Buat Work Order Baru
                    </a>
                    @if (session('success'))
                        <div class="bg-green-100 border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Judul Tugas</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Ditugaskan Kepada</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Batas Waktu</th>
                                    <th class="relative px-6 py-3"></th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse ($workOrders as $order)
                                    <tr>
                                        <td class="px-6 py-4 font-medium">{{ $order->title }}</td>
                                        <td class="px-6 py-4">{{ $order->assignee->name }}</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($order->status == 'Pending') bg-gray-100 text-gray-800 
                                                @elseif($order->status == 'In Progress') bg-yellow-100 text-yellow-800 
                                                @elseif($order->status == 'Completed') bg-green-100 text-green-800 
                                                @else bg-red-100 text-red-800 @endif">
                                                {{ $order->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm">{{ $order->due_date ? \Carbon\Carbon::parse($order->due_date)->format('d M Y') : '-' }}</td>
                                        <td class="px-6 py-4 text-right text-sm">
                                            <form action="{{ route('admin.penugasan.notify', $order->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Kirim notifikasi tugas ini ke {{ $order->assignee->name }}?');">
                                                @csrf
                                                <button type="submit" class="font-medium text-blue-600 hover:text-blue-900">Kirim Notifikasi</button>
                                            </form>
                                            <a href="{{ route('admin.penugasan.edit', $order->id) }}" class="font-medium text-indigo-600 hover:text-indigo-900 ml-4">Edit</a>
                                            <form action="{{ route('admin.penugasan.destroy', $order->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="font-medium text-red-600 hover:text-red-900 ml-4">Hapus</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            Belum ada Work Order.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $workOrders->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>