<tr class="hover:bg-gray-50 transition-colors">
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $index + 1 }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
        {{ $detail->namaBarang->nama_barang ?? 'Barang tidak ditemukan' }}
    </td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $detail->qty }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
        {{ $detail->berpajak }}
        @if($detail->keterangan_pajak)
        <span class="block text-xs text-gray-500">{{ $detail->keterangan_pajak }}</span>
        @endif
    </td>
    <td class="px-6 py-4 whitespace-nowrap">
        <div class="flex items-center space-x-2">
            @if($detail->status_persetujuan == 'approved')
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-green-100 text-green-800">Disetujui</span>
            @elseif($detail->status_persetujuan == 'rejected')
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                    Ditolak
                    @if($detail->keterangan_revisi)
                    <span class="block mt-1">{{ $detail->keterangan_revisi }}</span>
                    @endif
                </span>
            @else
                <span class="px-2 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800">Menunggu</span>
            @endif
            <a href="{{ route('keuangan.pengajuans.detail.edit', ['pengajuan' => $pengajuan->id, 'detail' => $detail->id]) }}" class="p-1 bg-amber-500 text-white rounded hover:bg-amber-600 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
            </a>
            <form action="{{ route('keuangan.pengajuans.detail.destroy', ['pengajuan' => $pengajuan->id, 'detail' => $detail->id]) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="button" onclick="if(confirm('Hapus item ini?')) this.closest('form').submit()" class="p-1 bg-red-500 text-white rounded hover:bg-red-600 transition-colors">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </button>
            </form>
        </div>
    </td>
</tr>