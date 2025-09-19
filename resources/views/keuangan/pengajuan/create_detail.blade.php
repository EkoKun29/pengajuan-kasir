@extends('layouts.app')
@section('title', 'Tambah Detail Pengajuan')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">

<div class="space-y-6">

    {{-- HEADER --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Tambah Detail Pengajuan</h1>
                <p class="text-sm text-gray-500 mt-1">Tambahkan barang untuk pengajuan #{{ $pengajuan->no_surat }}</p>
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

    {{-- FORM TAMBAH DETAIL --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Tambah Barang</h2>
        
        <div id="input-form" class="space-y-6">
            <div class="detail-item bg-gray-50 p-4 rounded-lg border border-gray-200">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama Barang</label>
                        <select id="nama_barang" name="nama_barang" required>
                            <option value="" disabled selected>Pilih Barang</option>
                            @foreach($namaBarangs as $barang)
                                <option value="{{ $barang->nama_barang }}">{{ $barang->nama_barang }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Quantity</label>
                        <input type="number" id="qty" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan jumlah" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Harga Satuan</label>
                        <input type="number" id="harga" class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Masukkan harga" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Berpajak</label>
                        <select id="berpajak" name="berpajak"
                            class="w-full border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" required> 
                            <option value="" disabled selected>Pilih Ket.Pajak</option>
                            <option value="BERPAJAK">BERPAJAK</option>
                            <option value="TIDAK BERPAJAK">TIDAK BERPAJAK</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">3M FAKTUR</label>
                        <select id="keterangan_pajak" name="keterangan_pajak" required>
                            <option value="" disabled selected>Pilih 3M Faktur</option>
                            @foreach($plotList as $plotItem)
                                <option value="{{ $plotItem->keterangan_pajak }}">{{ $plotItem->keterangan_pajak }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Akun Biaya</label>
                        <select id="keperluan_beban" name="keperluan_beban" required>
                            <option value="" disabled selected>Pilih Akun Biaya</option>
                            @foreach($plotList as $plotItem)
                                <option value="{{ $plotItem->keperluan_beban }}">{{ $plotItem->keperluan_beban }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="mt-4 flex justify-end">
                    <button type="button" id="add-item-btn" class="px-4 py-2 bg-blue-500 text-white rounded-lg hover:bg-blue-600 transition-colors font-medium flex items-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Tambahkan ke Daftar
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ITEMS TABLE --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Daftar Barang yang Akan Ditambahkan</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ket. Pajak</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">3M Faktur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Akun Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody id="items-table" class="bg-white divide-y divide-gray-200">
                    <tr id="no-items-row">
                        <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                            Belum ada barang yang ditambahkan
                        </td>
                    </tr>
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right font-semibold text-gray-700">Total:</td>
                        <td id="total-amount" class="px-6 py-3 text-left font-semibold text-gray-900">Rp 0</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <form action="{{ route('keuangan.pengajuans.detail.store', $pengajuan->id) }}" method="POST" id="detailForm">
            @csrf
            <!-- Hidden input untuk menyimpan data barang -->
            <input type="hidden" name="items_data" id="items-data" value="[]">
            
            <div class="flex justify-between mt-6">
                <div class="flex items-center text-blue-600">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm">Total pengajuan akan dihitung otomatis berdasarkan Qty × Harga</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('keuangan.pengajuans.show', $pengajuan->id) }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Batal</a>
                    <button type="submit" id="save-btn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium" disabled>Simpan Semua</button>
                </div>
            </div>
        </form>
    </div>

    {{-- EXISTING ITEMS --}}
    @if($pengajuan->detailPengajuans->count() > 0)
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-lg font-semibold mb-4">Barang yang sudah tersimpan</h2>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Barang</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Qty</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Harga</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Ket. Pajak</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">3M Faktur</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Akun Biaya</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach($pengajuan->detailPengajuans as $index => $detail)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $index + 1 }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $detail->nama_barang }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->qty }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->berpajak }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->keterangan_pajak }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $detail->keperluan_beban }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                            <a href="{{ route('keuangan.pengajuans.detail.edit', ['pengajuan' => $pengajuan->id, 'detail' => $detail->id]) }}" class="inline-flex items-center p-2 bg-amber-500 text-white rounded-lg hover:bg-amber-600 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>
                            <form action="{{ route('keuangan.pengajuans.detail.destroy', ['pengajuan' => $pengajuan->id, 'detail' => $detail->id]) }}" method="POST" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="button" onclick="confirmDelete(this.closest('form'))" class="inline-flex items-center p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-gray-50">
                    <tr>
                        <td colspan="4" class="px-6 py-3 text-right font-semibold text-gray-700">Total Pengajuan:</td>
                        <td class="px-6 py-3 text-left font-semibold text-gray-900">Rp {{ number_format($pengajuan->detailPengajuans->sum('total'), 0, ',', '.') }}</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    @endif
</div>


<script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>
<script>
function confirmDelete(form) {
    if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
        form.submit();
    }
}

document.addEventListener('DOMContentLoaded', function() {

    new TomSelect("#nama_barang", {
        create: false,
        sortField: {field: "text", direction: "asc"}
    });

    new TomSelect("#keterangan_pajak", {
        create: false,
        sortField: {field: "text", direction: "asc"}
    });

    new TomSelect("#keperluan_beban", {
        create: false,
        sortField: {field: "text", direction: "asc"}
    });

    // Array untuk menyimpan data barang yang akan ditambahkan
    let items = [];
    let totalAmount = 0;
    
    // Element references
    const addItemBtn = document.getElementById('add-item-btn');
    const itemsTable = document.getElementById('items-table');
    const noItemsRow = document.getElementById('no-items-row');
    const totalAmountEl = document.getElementById('total-amount');
    const saveBtn = document.getElementById('save-btn');
    const itemsDataInput = document.getElementById('items-data');
    
    // Fungsi untuk memformat angka sebagai mata uang
    function formatCurrency(amount) {
        return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(amount).replace('IDR', 'Rp');
    }
    
    // Fungsi untuk menambahkan item ke tabel
    function addItemToTable() {
        // Ambil nilai dari form
        const barang = document.getElementById('nama_barang').value;
        const qty = parseInt(document.getElementById('qty').value);
        const harga = parseInt(document.getElementById('harga').value);
        const berpajak = document.getElementById('berpajak').value;
        const keteranganPajak = document.getElementById('keterangan_pajak').value;
        const keperluanBeban = document.getElementById('keperluan_beban').value;
        
        // Validasi input
        if (!barang || isNaN(qty) || isNaN(harga) || qty <= 0 || harga < 0) {
            alert('Mohon isi data barang dengan lengkap dan benar.');
            return;
        }
        
        // Hitung total
        const total = qty * harga;
        
        // Tambahkan data ke array
        const item = {
            nama_barang: barang,
            qty: qty,
            harga: harga,
            total: total,
            berpajak: berpajak,
            keterangan_pajak: keteranganPajak,
            keperluan_beban: keperluanBeban
        };
        
        items.push(item);
        
        // Update total amount
        totalAmount += total;
        totalAmountEl.textContent = formatCurrency(totalAmount);
        
        // Update hidden input dengan data items yang sudah di-stringify
        itemsDataInput.value = JSON.stringify(items);
        
        // Update tampilan tabel
        updateTable();
        
        // Reset form
        resetForm();
        
        // Enable save button
        saveBtn.disabled = false;
    }
    
    // Fungsi untuk memperbarui tampilan tabel
    function updateTable() {
        // Hapus pesan "no items" jika ada data
        if (items.length > 0) {
            noItemsRow.style.display = 'none';
        } else {
            noItemsRow.style.display = 'table-row';
            saveBtn.disabled = true;
            return;
        }
        
        // Hapus semua baris tabel (kecuali pesan "no items")
        const rows = itemsTable.querySelectorAll('tr:not(#no-items-row)');
        rows.forEach(row => row.remove());
        
        // Tambahkan baris untuk setiap item
        items.forEach((item, index) => {
            const row = document.createElement('tr');
            row.className = 'hover:bg-gray-50 transition-colors';
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${index + 1}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.nama_barang}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.qty}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatCurrency(item.harga)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">${formatCurrency(item.total)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.berpajak}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.keterangan_pajak}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.keperluan_beban}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <button type="button" class="inline-flex items-center p-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors delete-item" data-index="${index}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                    </button>
                </td>
            `;
            
            itemsTable.appendChild(row);
            
            // Tambahkan event listener untuk tombol delete
            const deleteBtn = row.querySelector('.delete-item');
            deleteBtn.addEventListener('click', function() {
                removeItem(parseInt(this.dataset.index));
            });
        });
    }
    
    // Fungsi untuk menghapus item dari tabel
    function removeItem(index) {
        // Kurangi total amount
        totalAmount -= items[index].total;
        totalAmountEl.textContent = formatCurrency(totalAmount);
        
        // Hapus item dari array
        items.splice(index, 1);
        
        // Update hidden input
        itemsDataInput.value = JSON.stringify(items);
        
        // Update tampilan tabel
        updateTable();
    }
    
    // Fungsi untuk mereset form
    function resetForm() {
        document.getElementById('nama_barang').selectedIndex = 0;
        document.getElementById('qty').value = '';
        document.getElementById('harga').value = '';
        document.getElementById('berpajak').selectedIndex = 1;
        document.getElementById('keterangan_pajak').value = '';
        document.getElementById('keperluan_beban').value = '';
    }
    
    // Event listener untuk tombol "Tambahkan ke Daftar"
    addItemBtn.addEventListener('click', addItemToTable);
    
    // Update tabel saat halaman dimuat
    updateTable();
});
</script>
@endsection