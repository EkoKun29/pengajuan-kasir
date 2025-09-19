@extends('layouts.app')
@section('title', 'Edit Detail Pengajuan')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Detail Pengajuan</h1>
                <p class="text-sm text-gray-500 mt-1">Edit item barang untuk pengajuan #{{ $pengajuan->no_surat }}</p>
            </div>
            <a href="{{ route('keuangan.pengajuans.show', $pengajuan->id) }}" class="bg-white border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- PENGAJUAN INFO PANEL --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Informasi Pengajuan</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500">No. Surat</p>
                <p class="font-medium">{{ $pengajuan->no_surat }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Tanggal</p>
                <p class="font-medium">{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500">Karyawan</p>
                <p class="font-medium">{{ $pengajuan->nama_karyawan }} ({{ $pengajuan->divisi }})</p>
            </div>
        </div>
    </div>

    {{-- FORM EDIT DETAIL --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Edit Barang</h2>
        <form action="{{ route('keuangan.pengajuans.detail.update', ['pengajuan' => $pengajuan->id, 'detail' => $detail->id]) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="detail-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                        <select id="nama_barang" name="nama_barang" required>
                            <option value="" disabled>Pilih Barang</option>
                            @foreach($namaBarangs as $barang)
                                <option value="{{ $barang->nama_barang }}" {{ $detail->nama_barang == $barang->nama_barang ? 'selected' : '' }}>{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" id="qty" name="qty" value="{{ $detail->qty }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan jumlah" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan</label>
                        <input type="number" id="harga" name="harga" value="{{ $detail->harga }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan harga" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berpajak</label>
                        <select id="berpajak" name="berpajak" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="" disabled>Pilih Ket.Pajak</option>
                            <option value="BERPAJAK" {{ $detail->berpajak == 'BERPAJAK' ? 'selected' : '' }}>BERPAJAK</option>
                            <option value="TIDAK BERPAJAK" {{ $detail->berpajak == 'TIDAK BERPAJAK' ? 'selected' : '' }}>TIDAK BERPAJAK</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">3M FAKTUR</label>
                        <select id="keterangan_pajak" name="keterangan_pajak" required>
                            <option value="" disabled>Pilih 3M Faktur</option>
                            <option value="{{ $detail->keterangan_pajak }}" selected>{{ $detail->keterangan_pajak ?: 'Tidak Ada' }}</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <div class="flex justify-end mt-6 space-x-3">
                <a href="{{ route('keuangan.pengajuans.show', $pengajuan->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</a>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Tom Select untuk nama barang
    new TomSelect("#nama_barang", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
    
    // Tom Select untuk 3M Faktur
    new TomSelect("#keterangan_pajak", {
        create: true,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
    
    // Tom Select untuk Berpajak
    new TomSelect("#berpajak", {
        create: false,
        sortField: {
            field: "text",
            direction: "asc"
        }
    });
});
</script>
@endsection