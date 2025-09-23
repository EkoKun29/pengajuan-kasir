@extends('layouts.app')

@section('title', 'Detail Pengajuan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Detail Pengajuan</h1>
                <p class="text-sm text-gray-500 mt-1">Informasi lengkap pengajuan</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('keuangan.pengajuans.edit', $pengajuan->id) }}" class="bg-amber-500 text-white px-4 py-2.5 rounded-lg hover:bg-amber-600 transition-colors flex items-center gap-2 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Edit Pengajuan
                </a>
                <button onclick="printInvoice('{{ route('keuangan.pengajuans.invoice', $pengajuan) }}')" title="Print Invoice"
                    class="bg-green-500 text-white px-4 py-2.5 rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 9V2h12v7M6 18h12v4H6v-4zM6 14h12v4H6v-4zM6 10h12v4H6v-4z" />
                    </svg>
                    Print Invoice
                </button>
                <a href="{{ route('keuangan.pengajuans.index') }}" class="bg-white border border-gray-200 text-gray-600 px-4 py-2.5 rounded-lg hover:bg-gray-50 hover:text-gray-700 transition-colors flex items-center gap-2 text-sm font-medium">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    {{-- PENGAJUAN INFO --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 h-full">
                <h2 class="text-lg font-semibold mb-4 text-gray-900 pb-3 border-b border-gray-100">Informasi Pengajuan</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nomor Pengajuan</p>
                        <p class="font-mono text-gray-900">#{{ str_pad($pengajuan->id, 4, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nomor Surat</p>
                        <p class="text-gray-900">{{ $pengajuan->no_surat }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Tanggal Pengajuan</p>
                        <p class="text-gray-900">{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Nama Karyawan</p>
                        <p class="text-gray-900">{{ $pengajuan->nama_karyawan }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Divisi</p>
                        <p class="text-gray-900">{{ $pengajuan->divisi }}</p>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-gray-500">Plot yang Digunakan</p>
                        <p class="text-gray-900">{{ $pengajuan->plot ?: '-' }}</p>
                    </div>

                    <div>
                        <p class="text-sm font-medium text-gray-500">Status</p>
                        <p class="text-gray-900">{{ $pengajuan->status ?: '-' }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                    <h2 class="text-lg font-semibold text-gray-900">Detail Barang</h2>
                    <a href="{{ route('keuangan.pengajuans.detail.create', $pengajuan->id) }}" class="px-3 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm flex items-center gap-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Tambah Barang
                    </a>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barang</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Akun Biaya</th>
                                <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($pengajuan->detailPengajuans as $index => $detail)
                                @include('partials.detail-row', ['detail' => $detail, 'index' => $index])
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                        Tidak ada data detail pengajuan
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td colspan="4" class="px-6 py-3 text-right text-sm font-semibold text-gray-900">TOTAL</td>
                                <td class="px-6 py-3 text-left text-sm font-semibold text-gray-900">Rp {{ number_format($pengajuan->total, 0, ',', '.') }}</td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function printInvoice(url) {
    let printWindow = window.open(url, 'PrintInvoice', 'width=1000,height=800');
    printWindow.onload = function() {
        setTimeout(function() {
            printWindow.print();
        }, 1000);
    };
}
</script>
@endsection