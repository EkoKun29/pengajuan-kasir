@extends('layouts.app')
@section('title', 'Manajemen Pengajuan')

@section('content')
<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Manajemen Pengajuan</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola Pengajuan</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                {{-- Search Form --}}
                <form method="GET" action="{{ route('keuangan.pengajuans.index') }}" class="flex gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pengajuan....."
                               class="border-gray-300 rounded-lg px-4 py-2.5 text-sm w-64 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2.5 rounded-lg transition-colors font-medium">Cari</button>
                </form>
                {{-- Add Button --}}
                <a href="{{ route('keuangan.pengajuans.create') }}" class="bg-blue-600 text-white px-4 py-2.5 rounded-lg hover:bg-blue-700 transition-colors font-medium flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Pengajuan
                </a>
            </div>
        </div>
    </div>
    
    {{-- STATUS CARDS --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Approved Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="rounded-full bg-green-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Disetujui</h3>
                    <p class="text-2xl font-bold text-green-600">
                        {{ App\Models\DetailPengajuan::whereHas('pengajuan', function($query) {
                            $query->where('user_id', Auth::id());
                        })->where('status_persetujuan', 'approved')->count() }}
                    </p>
                    <p class="text-sm text-gray-500">Item yang disetujui</p>
                </div>
            </div>
        </div>
        
        {{-- Pending Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="rounded-full bg-yellow-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Menunggu</h3>
                    <p class="text-2xl font-bold text-yellow-600">
                        {{ App\Models\DetailPengajuan::whereHas('pengajuan', function($query) {
                            $query->where('user_id', Auth::id());
                        })->where(function($query) {
                            $query->whereNull('status_persetujuan')
                                ->orWhere('status_persetujuan', 'pending')
                                ->orWhere('status_persetujuan', 'menunggu');
                        })->count() }}
                    </p>
                    <p class="text-sm text-gray-500">Item yang menunggu persetujuan</p>
                </div>
            </div>
        </div>
        
        {{-- Rejected Card --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center">
                <div class="rounded-full bg-red-100 p-3 mr-4">
                    <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-semibold text-gray-900">Ditolak</h3>
                    <p class="text-2xl font-bold text-red-600">
                        {{ App\Models\DetailPengajuan::whereHas('pengajuan', function($query) {
                            $query->where('user_id', Auth::id());
                        })->where('status_persetujuan', 'rejected')->count() }}
                    </p>
                    <p class="text-sm text-gray-500">Item yang ditolak</p>
                </div>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        {{-- <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th> --}}
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengajuans as $pengajuan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pengajuan->tgl_pengajuan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pengajuan->no_surat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pengajuan->nama_karyawan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pengajuan->divisi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $pengajuan->total_item }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            Rp {{ number_format($pengajuan->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            {{-- Detail Button --}}
                            <a href="{{ route('keuangan.pengajuans.show', $pengajuan) }}" title="Detail"
                                    class="inline-flex items-center p-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            {{-- Edit Button --}}
                            <a href="{{ route('keuangan.pengajuans.edit', $pengajuan) }}" title="Edit"
                                    class="inline-flex items-center p-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            {{-- Print Button --}}
                            <button onclick="printInvoice('{{ route('keuangan.pengajuans.invoice', $pengajuan) }}')" title="Print Nota/Invoice"
                                class="inline-flex items-center p-2 bg-green-500 text-white rounded-lg hover:bg-green-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 9V2h12v7M6 18h12v4H6v-4zM6 14h12v4H6v-4zM6 10h12v4H6v-4z" />
                                </svg>
                            </button>
                            {{-- Delete Button --}}
                            <form action="{{ route('keuangan.pengajuans.destroy', $pengajuan) }}" method="POST" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="button" title="Hapus" onclick="confirmDelete(this.closest('form'), '{{ $pengajuan->no_surat }}')"
                                        class="inline-flex items-center p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data Pengajuan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- PAGINATION --}}
    @if(isset($pengajuans) && method_exists($pengajuans, 'links'))
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">{{ $pengajuans->links() }}</div>
    @endif
</div>

<script>
function confirmDelete(form, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus pengajuan ${name}?`)) {
        form.submit();
    }
}

function printInvoice(url) {
    // Buka popup window kecil
    let printWindow = window.open(url, 'PrintInvoice', 'width=1000,height=800');
    
    // Tunggu sampai konten selesai dimuat
    printWindow.onload = function() {
        // Berikan sedikit waktu agar CSS dan gambar dimuat dengan sempurna
        setTimeout(function() {
            // Tampilkan print dialog
            printWindow.print();
            
            // Tutup jendela setelah print dialog ditutup (opsional)
            // printWindow.onafterprint = function() {
            //     printWindow.close();
            // };
        }, 1000);
    };
}
</script>
@endsection