@extends('layouts.app')
@section('title', 'Tambah Pengajuan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Pengajuan</h1>
                <p class="text-sm text-gray-500 mt-1">Form pembuatan pengajuan baru</p>
            </div>
            <a href="{{ route('keuangan.pengajuans.index') }}" class="bg-white border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- FORM --}}
    <form action="{{ route('keuangan.pengajuans.store') }}" method="POST" class="space-y-6">
        @csrf
        
        {{-- GENERAL INFO --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <h2 class="text-lg font-semibold mb-4">Informasi Pengajuan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengajuan</label>
                    <input type="date" name="tgl_pengajuan" value="{{ old('tgl_pengajuan', date('Y-m-d')) }}" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Surat</label>
                    <input type="text" name="no_surat" value="{{ $contohNomorSurat ?? 'P/'.date('Ymd').'/0001' }}" class="w-full border-gray-300 bg-gray-50 text-blue-600 rounded-lg px-3 py-2" readonly>
                    <p class="text-xs text-gray-500 mt-1">Nomor surat akan otomatis digenerate dengan format P/YYYYMMDD/XXXX</p>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Karyawan</label>
                    <select name="nama_karyawan" id="nama_karyawan" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Pilih karyawan</option>
                        @foreach($namaKaryawans as $karyawan)
                            <option value="{{ $karyawan->nama_karyawan }}" data-divisi="{{ $karyawan->divisi }}">{{ $karyawan->nama_karyawan }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Divisi</label>
                    <input 
                        type="text" 
                        name="divisi" 
                        id="divisi" 
                        class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
                        placeholder="Masukkan divisi" 
                        readonly required
                    >
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Plot</label>
                    <select name="plot" id="plot" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required>
                        <option value="" disabled selected>Pilih plot</option>
                        @foreach($plotList as $plotItem)
                            <option value="{{ $plotItem->plot }}">{{ $plotItem->plot }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Keperluan/Beban</label>
                    <input 
                        type="text" 
                        name="keperluan_beban" 
                        id="keperluan_beban" 
                        class="w-full border-gray-300 rounded-lg px-3 py-2 bg-gray-50 focus:ring-0 focus:border-gray-300" 
                        placeholder="Keperluan/Beban akan terisi otomatis" 
                        readonly required
                    >
                </div>
            </div>
        </div>

        {{-- INFO PANEL --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center space-x-4">
                <div class="flex-shrink-0 bg-blue-100 text-blue-600 p-3 rounded-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <h3 class="text-md font-medium text-gray-900">Informasi Detail Pengajuan</h3>
                    <p class="text-sm text-gray-500 mt-1">Setelah menyimpan informasi pengajuan ini, Anda akan diarahkan ke halaman untuk menambahkan detail barang yang diajukan.</p>
                </div>
            </div>
        </div>

        {{-- SUBMIT BUTTON --}}
        <div class="flex justify-end space-x-4">
            <a href="{{ route('keuangan.pengajuans.index') }}" class="bg-gray-100 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</a>
            <button type="submit" class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium">Simpan Pengajuan</button>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Auto-fill divisi when nama_karyawan is selected
    document.getElementById('nama_karyawan').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const divisiValue = selectedOption.getAttribute('data-divisi');
        const divisiField = document.getElementById('divisi');
        
        // Set the divisi value from the selected karyawan
        if (divisiField) {
            divisiField.value = divisiValue || '';
        }
    });

    // Data akun biaya untuk keperluan beban
    const akunBiayaList = @json($akunBiayaList);
    
    // Auto-fill keperluan_beban when plot is selected
    document.getElementById('plot').addEventListener('change', function() {
        const selectedPlot = this.value;
        const keperluanBebanField = document.getElementById('keperluan_beban');
        
        // Reset field
        keperluanBebanField.value = '';
        
        // Find the first matching keperluan_beban for the selected plot
        const matchingItem = akunBiayaList.find(item => 
            item.plot === selectedPlot && item.keperluan_beban && item.keperluan_beban.trim() !== ''
        );
        
        // Set the keperluan_beban value if found
        if (matchingItem) {
            keperluanBebanField.value = matchingItem.keperluan_beban;
        } else {
            keperluanBebanField.value = 'Tidak ada data keperluan/beban untuk plot ini';
        }
    });
});
</script>
@endsection