@extends('layouts.app')
@section('title', 'Detail Pengajuan ' . $pengajuan->no_surat)

@section('content')
<div x-data="{ 
    statusModals: {},
    toggleStatusModal(detailId) {
        this.statusModals[detailId] = !this.statusModals[detailId];
        document.body.style.overflow = this.statusModals[detailId] ? 'hidden' : '';
    },
    submitFormOnKeyEnter(event, formId) {
        if (event.key === 'Enter') {
            event.preventDefault();
            document.getElementById(formId).submit();
        }
    }
}" class="space-y-6">
    {{-- HEADER & ACTIONS --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Pengajuan #{{ $pengajuan->no_surat }}</h1>
                <p class="text-sm text-gray-500 mt-1">Tanggal: {{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                <a href="{{ route('direktur.pengajuan.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
                <form action="{{ route('direktur.pengajuan.approve-all', $pengajuan) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui semua item?');">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="inline-flex items-center justify-center px-4 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Setujui Semua
                    </button>
                </form>
            </div>
        </div>
        
        {{-- STATS --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-gray-500">Total Item</p>
                <p class="text-2xl font-bold text-gray-900 mt-1">{{ $stats['total_items'] }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-green-700">Disetujui</p>
                <p class="text-2xl font-bold text-green-800 mt-1">{{ $stats['approved'] }}</p>
            </div>
            <div class="bg-red-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-red-700">Ditolak</p>
                <p class="text-2xl font-bold text-red-800 mt-1">{{ $stats['rejected'] }}</p>
            </div>
            <div class="bg-yellow-50 p-4 rounded-lg">
                <p class="text-sm font-medium text-yellow-700">Menunggu</p>
                <p class="text-2xl font-bold text-yellow-800 mt-1">{{ $stats['pending'] }}</p>
            </div>
        </div>
        
        {{-- PENGAJUAN INFO --}}
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-700">Informasi Pengajuan</h3>
                <dl class="mt-2 space-y-1">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Nomor Surat</dt>
                        <dd class="text-sm font-medium">{{ $pengajuan->no_surat }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Tanggal</dt>
                        <dd class="text-sm font-medium">{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}</dd>
                    </div>
                </dl>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-700">Informasi Pengaju</h3>
                <dl class="mt-2 space-y-1">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Nama</dt>
                        <dd class="text-sm font-medium">{{ $pengajuan->nama_karyawan }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Divisi</dt>
                        <dd class="text-sm font-medium">{{ $pengajuan->divisi }}</dd>
                    </div>
                </dl>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg">
                <h3 class="font-medium text-gray-700">Informasi Lainnya</h3>
                <dl class="mt-2 space-y-1">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Plot</dt>
                        <dd class="text-sm font-medium">{{ $pengajuan->plot ?? '-' }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Total Anggaran</dt>
                        <dd class="text-sm font-medium">Rp {{ number_format($pengajuan->total, 0, ',', '.') }}</dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-lg font-semibold text-gray-900">Daftar Item Pengajuan</h2>
            <p class="text-sm text-gray-500 mt-1">Setujui atau tolak setiap item pengajuan</p>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Barang</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keterangan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengajuan->detailPengajuans as $index => $detail)
                    <tr class="hover:bg-gray-50 transition-colors" x-data="{ quickEditOpen: false }">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->nama_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $detail->qty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusClass = [
                                    'menunggu' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-green-100 text-green-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'pending' => 'bg-gray-100 text-gray-800'
                                ][$detail->status_persetujuan ?? 'menunggu'];
                                
                                $statusText = [
                                    'menunggu' => 'Menunggu',
                                    'approved' => 'Disetujui',
                                    'rejected' => 'Ditolak',
                                    'pending' => 'Tertunda'
                                ][$detail->status_persetujuan ?? 'menunggu'];
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                {{ $statusText }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $detail->keterangan_revisi ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div x-show="!quickEditOpen" class="flex space-x-2">
                                <form id="quick-approve-{{ $detail->id }}" method="POST" action="{{ route('direktur.pengajuan.update-status', ['pengajuan' => $pengajuan, 'detail' => $detail]) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_persetujuan" value="approved">
                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-green-600 text-white text-xs rounded hover:bg-green-700 transition-colors" 
                                           title="Setujui item ini"
                                           @keydown="submitFormOnKeyEnter($event, 'quick-approve-{{ $detail->id }}')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                                <form id="quick-reject-{{ $detail->id }}" method="POST" action="{{ route('direktur.pengajuan.update-status', ['pengajuan' => $pengajuan, 'detail' => $detail]) }}" class="inline">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status_persetujuan" value="rejected">
                                    <input type="hidden" name="keterangan_revisi" value="Ditolak oleh direktur">
                                    <button type="submit" class="inline-flex items-center px-2 py-1 bg-red-600 text-white text-xs rounded hover:bg-red-700 transition-colors" 
                                           title="Tolak item ini"
                                           @keydown="submitFormOnKeyEnter($event, 'quick-reject-{{ $detail->id }}')">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </button>
                                </form>
                                <button @click="toggleStatusModal({{ $detail->id }})" class="inline-flex items-center px-2 py-1 bg-blue-600 text-white text-xs rounded hover:bg-blue-700 transition-colors" title="Ubah status dengan catatan">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </button>
                            </div>
                        </td>
                    </tr>
                    
                    {{-- STATUS MODAL --}}
                    <div x-show="statusModals[{{ $detail->id }}]" x-cloak 
                         class="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
                        <div @click.away="toggleStatusModal({{ $detail->id }})" 
                             class="bg-white rounded-xl shadow-xl w-full max-w-md overflow-hidden">
                            <div class="px-6 py-4 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900">Ubah Status Item</h3>
                            </div>
                            <div class="p-6">
                                <form method="POST" action="{{ route('direktur.pengajuan.update-status', ['pengajuan' => $pengajuan, 'detail' => $detail]) }}">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="mb-4">
                                        <p class="text-sm text-gray-600">Item: <span class="font-medium">{{ $detail->nama_barang }}</span></p>
                                        <p class="text-sm text-gray-600">Qty: <span class="font-medium">{{ $detail->qty }}</span></p>
                                        <p class="text-sm text-gray-600">Total: <span class="font-medium">Rp {{ number_format($detail->total, 0, ',', '.') }}</span></p>
                                    </div>
                                    
                                    <div class="mb-4" x-data="{ selectedStatus: '{{ $detail->status_persetujuan == 'pending' || $detail->status_persetujuan == 'menunggu' ? 'pending' : $detail->status_persetujuan }}' }">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Persetujuan</label>
                                        <div class="grid grid-cols-3 gap-2">
                                            <label class="flex items-center justify-center p-2.5 border rounded-lg cursor-pointer transition-all" 
                                                   :class="{ 'bg-green-50 border-green-500 ring-2 ring-green-300': selectedStatus === 'approved' }">
                                                <input type="radio" x-model="selectedStatus" name="status_persetujuan" value="approved" class="hidden">
                                                <div class="text-center">
                                                    <svg class="w-6 h-6 mx-auto" :class="selectedStatus === 'approved' ? 'text-green-600' : 'text-gray-400'" 
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                    <span class="block mt-1 text-sm font-medium" :class="selectedStatus === 'approved' ? 'text-green-700' : 'text-gray-700'">Setujui</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center justify-center p-2.5 border rounded-lg cursor-pointer transition-all"
                                                   :class="{ 'bg-red-50 border-red-500 ring-2 ring-red-300': selectedStatus === 'rejected' }">
                                                <input type="radio" x-model="selectedStatus" name="status_persetujuan" value="rejected" class="hidden">
                                                <div class="text-center">
                                                    <svg class="w-6 h-6 mx-auto" :class="selectedStatus === 'rejected' ? 'text-red-600' : 'text-gray-400'" 
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                                    </svg>
                                                    <span class="block mt-1 text-sm font-medium" :class="selectedStatus === 'rejected' ? 'text-red-700' : 'text-gray-700'">Tolak</span>
                                                </div>
                                            </label>
                                            <label class="flex items-center justify-center p-2.5 border rounded-lg cursor-pointer transition-all"
                                                   :class="{ 'bg-yellow-50 border-yellow-500 ring-2 ring-yellow-300': selectedStatus === 'pending' }">
                                                <input type="radio" x-model="selectedStatus" name="status_persetujuan" value="pending" class="hidden">
                                                <div class="text-center">
                                                    <svg class="w-6 h-6 mx-auto" :class="selectedStatus === 'pending' ? 'text-yellow-600' : 'text-gray-400'" 
                                                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                    </svg>
                                                    <span class="block mt-1 text-sm font-medium" :class="selectedStatus === 'pending' ? 'text-yellow-700' : 'text-gray-700'">Tunda</span>
                                                </div>
                                            </label>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-4" x-data="{ showKeterangan: '{{ $detail->status_persetujuan }}' === 'rejected' }">
                                        <div x-show="$data.selectedStatus === 'rejected'" x-transition>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                                Catatan/Keterangan Penolakan <span class="text-red-500">*</span>
                                            </label>
                                            <textarea name="keterangan_revisi" rows="3" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                     placeholder="Masukkan alasan penolakan..."
                                                     :required="$data.selectedStatus === 'rejected'">{{ $detail->keterangan_revisi }}</textarea>
                                            <p class="mt-1 text-xs text-gray-500">Wajib diisi jika status ditolak</p>
                                        </div>
                                        
                                        <div x-show="$data.selectedStatus !== 'rejected'" x-transition>
                                            <label class="block text-sm font-medium text-gray-700 mb-2">Catatan/Keterangan (opsional)</label>
                                            <textarea name="keterangan_revisi" rows="2" class="w-full border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                                                     placeholder="Masukkan catatan tambahan...">{{ $detail->status_persetujuan !== 'rejected' ? $detail->keterangan_revisi : '' }}</textarea>
                                        </div>
                                    </div>
                                    
                                    <div class="flex justify-end space-x-3">
                                        <button type="button" @click="toggleStatusModal({{ $detail->id }})" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">
                                            Batal
                                        </button>
                                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">
                                            Simpan
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada item dalam pengajuan ini</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
[x-cloak] { display: none !important; }
</style>
@endsection