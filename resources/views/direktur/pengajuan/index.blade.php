@extends('layouts.app')
@section('title', 'Pengajuan yang Memerlukan Persetujuan')

@section('content')
<div class="space-y-6">
    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Daftar Pengajuan</h1>
                <p class="text-sm text-gray-500 mt-1">Kelola dan setujui pengajuan anggaran</p>
            </div>
            <div class="flex flex-col sm:flex-row gap-3 w-full sm:w-auto">
                {{-- Search & Filter Form --}}
                <form method="GET" action="{{ route('direktur.pengajuan.index') }}" class="flex gap-2">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no surat, karyawan..."
                               class="border-gray-300 rounded-lg px-4 py-2.5 text-sm w-64 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors">
                        <svg class="absolute right-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <select name="status" class="border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Semua Status</option>
                        <option value="menunggu" {{ request('status') == 'menunggu' ? 'selected' : '' }}>Menunggu</option>
                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Disetujui</option>
                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
                    </select>
                    <button type="submit" class="bg-gray-100 hover:bg-gray-200 text-gray-700 text-sm px-4 py-2.5 rounded-lg transition-colors font-medium">Filter</button>
                </form>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No. Surat</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Karyawan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Divisi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total Item</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @forelse($pengajuans as $pengajuan)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $loop->iteration }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $pengajuan->no_surat }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ \Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d M Y') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pengajuan->nama_karyawan }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pengajuan->divisi }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $pengajuan->detailPengajuans->count() }} item</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $totalItems = $pengajuan->detailPengajuans->count();
                                $approvedItems = $pengajuan->detailPengajuans->where('status_persetujuan', 'approved')->count();
                                $percentage = $totalItems > 0 ? ($approvedItems / $totalItems) * 100 : 0;
                            @endphp
                            <div class="flex items-center">
                                <div class="w-full bg-gray-200 rounded-full h-2.5">
                                    <div class="bg-blue-600 h-2.5 rounded-full" style="width: {{ $percentage }}%"></div>
                                </div>
                                <span class="ml-2 text-xs font-medium text-gray-600">{{ number_format($percentage) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <a href="{{ route('direktur.pengajuan.show', $pengajuan) }}" class="inline-flex items-center px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors font-medium text-sm">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center justify-center text-gray-500">
                                <svg class="w-12 h-12 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                <p class="text-lg font-medium">Tidak ada data pengajuan</p>
                                <p class="text-sm text-gray-400 mt-1">Belum ada pengajuan yang memerlukan persetujuan</p>
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
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
        {{ $pengajuans->links() }}
    </div>
    @endif
</div>
@endsection