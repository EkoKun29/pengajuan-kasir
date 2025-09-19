<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $pengajuan->no_surat }}</title>
    <style>
        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            margin: 0;
            padding: 0;
            color: #333;
            background-color: white;
            font-size: 11px;
        }
        .invoice-container {
            width: 29.7cm;
            margin: 0 auto;
            padding: 1cm;
            box-sizing: border-box;
        }
        
        /* Header Perusahaan */
        .company-header {
            border: 2px solid #000;
            padding: 10px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
        }
        .company-logo {
            width: 80px;
            height: 80px;
            margin-right: 20px;
        }
        .company-info {
            flex: 1;
            text-align: center;
        }
        .company-info h1 {
            margin: 0;
            font-size: 18px;
            font-weight: bold;
            color: #000;
        }
        .company-info p {
            margin: 2px 0;
            font-size: 10px;
            line-height: 1.2;
        }
        
        /* Judul Form */
        .form-title {
            text-align: center;
            margin: 20px 0;
            background-color: #f0f0f0;
            padding: 8px;
            border: 1px solid #000;
        }
        .form-title h2 {
            margin: 0;
            font-size: 12px;
            font-weight: bold;
        }
    
        /* Tabel */
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 10px;
        }
        .items-table th,
        .items-table td {
            border: 1px solid #000;
            padding: 5px 8px;
            text-align: left;
            vertical-align: middle;
        }
        .items-table th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .items-table .text-center {
            text-align: center;
        }
        .items-table .text-right {
            text-align: right;
        }
        
        /* Total */
        .total-section {
            margin-top: 10px;
            text-align: right;
        }
        .total-row {
            background-color: #ffff99;
            font-weight: bold;
        }
        
        /* Area Tanda Tangan */
        .signature-section {
            margin-top: 30px;
        }
        .full-width {
            width: 100%;
        }
        .sig-categories {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        .sig-category-title {
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }
        .pembuat {
            width: 26%; /* For 2 names */
        }
        .menyetujui {
            width: 39%; /* For 3 names */
        }
        .mengetahui {
            width: 26%; /* For 2 names */
        }
        .sig-row {
            display: flex;
            justify-content: space-between;
        }
        .sig-person-inline {
            width: 13%;
            text-align: center;
            font-size: 10px;
        }
        .sig-person-inline p {
            margin: 2px 0;
        }
        .sig-line {
            border-bottom: 1px solid #000;
            width: 100%;
            margin: 35px auto 5px auto;
        }
        
        @media print {
            @page {
                size: A4 landscape;
                margin: 0.5cm;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                width: 100%;
                padding: 0.5cm;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- Header Perusahaan -->
        <div class="company-header">
            <img src="{{ asset('/dist/img/LOGO_ALIANSYAH.png') }}" alt="Logo CV. ALIANSYAH" class="company-logo">
            <div class="company-info">
                <h1>CV.ALIANSYAH</h1>
                <h2>Penyedia Pupuk dan Obat Pertanian</h2>
                <p>Desa Winong RT 02 RW 01 Kec. Winong Kab. Pati</p>
                <p>Telp/Fax : 0295 410 1988</p>
                <p>Email : Aliansyah_50@yahoo.com</p>
            </div>
        </div>

        <!-- Judul Form -->
        <div class="form-title">
            <h2>PENGAJUAN ANGGARAN RUTIN DO. DAN DISTRIBUSI</h2>
        </div>

        <!-- Info Dokumen -->
        <div class="document-info">
            <p>NOMOR SURAT : {{ $pengajuan->no_surat }}</p>
            <p>DIVISI : {{ $pengajuan->divisi }}</p>
        </div>

        <!-- Tabel Items -->
        <table class="items-table">
            <thead>
                <tr>
                    <th style="width: 5%;">NO</th>
                    <th style="width: 25%;">PLOT YANG DIGUNAKAN</th>
                    <th style="width: 20%;">AKUN BIAYA</th>
                    <th style="width: 20%;">YANG MENGAJUKAN</th>
                    <th style="width: 15%;">BARANG YANG DIAJUKAN</th>
                    <th style="width: 5%;">QTY</th>
                    <th style="width: 15%;">HARGA</th>
                    <th style="width: 15%;">TOTAL</th>
                    <th style="width: 15%;">KETERANGAN PAJAK</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->detailPengajuans as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $pengajuan->plot }}</td>
                    <td>BIAYA OPERASIONAL</td>
                    <td>{{ $pengajuan->nama_karyawan }}</td>
                    <td>{{ $detail->nama_barang }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                    <td>{{ $detail->keterangan_pajak ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="total-row">
                    <td colspan="7" class="text-center"><strong>GRAND TOTAL</strong></td>
                    <td colspan="2" class="text-right"><strong>Rp {{ number_format($totalAmount, 0, ',', '.') }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <!-- Area Tanda Tangan -->
        <div class="signature-section">
            <div class="full-width">
                <!-- Kategori -->
                <div class="sig-categories">
                    <div class="sig-category-title pembuat">PEMBUAT</div>
                    <div class="sig-category-title menyetujui">MENYETUJUI</div>
                    <div class="sig-category-title mengetahui">MENGETAHUI</div>
                </div>
                
                <!-- Semua tanda tangan berjejer -->
                <div class="sig-row">
                    <!-- Pembuat 1 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Atok Novianto</strong></p>
                        <p>Kasir</p>
                    </div>
                    
                    <!-- Pembuat 2 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Ahmad Fadholi</strong></p>
                        <p>Admin Gudang</p>
                    </div>
                    
                    <!-- Menyetujui 1 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Ahmad Arrowi</strong></p>
                        <p>Manager Gd dan Distribusi</p>
                    </div>
                    
                    <!-- Menyetujui 2 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Mts Usman</strong></p>
                        <p>SPV Bagian</p>
                    </div>
                    
                    <!-- Menyetujui 3 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Tri Handoko</strong></p>
                        <p>Direktur</p>
                    </div>
                    
                    <!-- Mengetahui 1 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Fathrul Aminnah</strong></p>
                        <p>Ka Adm Keuangan</p>
                    </div>
                    
                    <!-- Mengetahui 2 -->
                    <div class="sig-person-inline">
                        <div class="sig-line"></div>
                        <p><strong>Helmy Kurniawan</strong></p>
                        <p>Manager Keuangan</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>