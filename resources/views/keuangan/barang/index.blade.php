@extends('layouts.app')
@section('title', 'Manajemen Barang')

@section('content')
<div x-data="{ 
    openCreate: false,
    editModals: {},
    detailModals: {},
    toggleModal(type, barangId = null) {
        if (type === 'create') {
            this.openCreate = !this.openCreate;
            document.body.style.overflow = this.openCreate ? 'hidden' : '';
        } else {
            this[type + 'Modals'][barangId] = !this[type + 'Modals'][barangId];
            document.body.style.overflow = this[type + 'Modals'][barangId] ? 'hidden' : '';
        }
    }
}" class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Barang</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola Barang</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                {{-- Search Form --}}
                <form method="GET" action="{{ auth()->user()->role === 'keuangan' ? route('keuangan.barangs.index') : route('direktur.barangs.index') }}" class="flex gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari barang....."
                               class="border-gray-300 rounded-lg px-4 py-2.5 text-sm w-64 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2.5 rounded-lg transition-colors font-medium">Cari</button>
                </form>
                {{-- Add Button --}}
                <button @click="toggleModal('create')" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Barang
                </button>
            </div>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($barangs as $barang)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $barang->nama_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            {{-- Detail Button --}}
                            <button @click="toggleModal('detail', {{ $barang->id }})" title="Detail"
                                    class="inline-flex items-center p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </button>
                            {{-- Edit Button --}}
                            <button @click="toggleModal('edit', {{ $barang->id }})" title="Edit"
                                    class="inline-flex items-center p-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </button>
                            {{-- Delete Button --}}
                            <form action="{{ route('keuangan.barangs.destroy', $barang) }}" method="POST" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="button" title="Hapus" onclick="confirmDelete(this.closest('form'), '{{ $barang->name }}')"
                                        class="inline-flex items-center p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- DETAIL MODAL --}}
                    <div x-show="detailModals[{{ $barang->id }}]" x-cloak 
                         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                        <div @click.away="toggleModal('detail', {{ $barang->id }})" 
                             class="bg-white rounded-xl shadow-xl w-full max-w-lg">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h2 class="text-lg font-semibold text-gray-900">Detail Barang</h2>
                                <button @click="toggleModal('detail', {{ $barang->id }})" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-6 py-6">
                                <div class="text-center mb-6">
                                    <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-purple-600 rounded-full mx-auto flex items-center justify-center text-white text-2xl font-bold">
                                        {{ strtoupper(substr($barang->nama_barang, 0, 1)) }}
                                    </div>
                                    <h3 class="mt-4 text-xl font-semibold text-gray-900">{{ $barang->nama_barang }}</h3>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-500">Barang ID</dt>
                                        <dd class="mt-1 text-sm font-mono text-gray-900">#{{ str_pad($barang->id, 4, '0', STR_PAD_LEFT) }}</dd>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-500">Terdaftar</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $barang->created_at?->format('d M Y H:i') ?? '-' }}</dd>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4">
                                        <dt class="text-sm font-medium text-gray-500">Update Terakhir</dt>
                                        <dd class="mt-1 text-sm text-gray-900">{{ $barang->updated_at?->format('d M Y H:i') ?? '-' }}</dd>
                                    </div>
                                </div>
                            </div>
                            <div class="px-6 py-4 border-t border-gray-200 flex justify-end">
                                <button @click="toggleModal('detail', {{ $barang->id }})" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- EDIT MODAL --}}
                    <div x-show="editModals[{{ $barang->id }}]" x-cloak 
                         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                        <div @click.away="toggleModal('edit', {{ $barang->id }})" 
                             class="bg-white rounded-xl shadow-xl w-full max-w-lg">
                            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                                <h2 class="text-lg font-semibold text-gray-900">Edit Barang</h2>
                                <button @click="toggleModal('edit', {{ $barang->id }})" class="text-gray-400 hover:text-gray-600">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="px-6 py-4">
                                <form method="POST" action="{{ route('keuangan.barangs.update', $barang) }}" class="space-y-4">
                                    @csrf @method('PUT')
                                    <div class="grid gap-4">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                                            <input type="text" name="nama_barang" value="{{ $barang->nama_barang }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" required>
                                        </div>
                                    </div>
                                    <div class="px-6">
                                        <small class="form-text text-muted">
                                        👉 Gunakan huruf <b>KAPITAL</b>.<br>
                                        </small>
                                    </div>
                                    <div class="flex justify-end space-x-3 pt-4">
                                        <button type="button" @click="toggleModal('edit', {{ $barang->id }})" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Simpan</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data Barang</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(isset($barangs) && method_exists($barangs, 'links'))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">{{ $barangs->links() }}</div>
    @endif

    {{-- CREATE MODAL --}}
    <div x-show="openCreate" x-cloak class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
        <div @click.away="toggleModal('create')" class="bg-white rounded-xl shadow-xl w-full max-w-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Tambah Barang</h2>
            </div>
            <div class="px-6 py-4">
                <form method="POST" action="{{ route('keuangan.barangs.store') }}" id="createForm" class="space-y-4">
                    @csrf
                    <div class="grid gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                            <input type="text" name="nama_barang" value="{{ old('nama_barang') }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 uppercase" placeholder="Masukkan nama" required>
                        </div>
                </form>
            </div>
            </div>
            <div class="px-6"><small class="form-text text-muted">
                👉 Gunakan huruf <b>KAPITAL</b>.<br>
            </small>
            </div>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-3">
                <button @click="toggleModal('create')" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</button>
                <button type="submit" form="createForm" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Simpan</button>
            </div>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
.modal-scroll::-webkit-scrollbar { width: 6px; }
.modal-scroll::-webkit-scrollbar-track { background: #f1f5f9; border-radius: 3px; }
.modal-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 3px; }
.modal-scroll::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
</style>
@endsection