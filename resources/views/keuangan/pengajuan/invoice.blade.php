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
        }
        .invoice-container {
            width: 21cm; /* A4 width */
            margin: 0 auto;
            padding: 2cm;
            box-sizing: border-box;
        }
        .invoice-header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .invoice-header h1 {
            margin: 0 0 5px 0;
            color: #333;
            font-size: 24px;
        }
        .invoice-header p {
            margin: 0;
            font-size: 14px;
            color: #666;
        }
        .invoice-details {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
        }
        .invoice-details-left, .invoice-details-right {
            flex: 1;
        }
        .invoice-details-right {
            text-align: right;
        }
        .invoice-details h3 {
            margin-top: 0;
            margin-bottom: 5px;
            font-size: 16px;
            color: #333;
        }
        .invoice-details p {
            margin: 0 0 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 10px;
            font-size: 14px;
            text-align: left;
        }
        table th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .invoice-total {
            text-align: right;
            margin-top: 30px;
            font-size: 16px;
            font-weight: bold;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 14px;
            color: #666;
        }
        .signature-area {
            margin-top: 60px;
            display: flex;
            justify-content: space-between;
        }
        .signature-box {
            width: 45%;
            text-align: center;
        }
        .signature-line {
            margin-top: 80px;
            border-top: 1px solid #333;
            margin-bottom: 5px;
            width: 80%;
            display: inline-block;
        }
        @media print {
            .print-button {
                display: none;
            }
            @page {
                size: A4;
                margin: 0;
            }
            body {
                margin: 0;
                padding: 0;
            }
            .invoice-container {
                width: 100%;
                padding: 2cm;
                box-sizing: border-box;
                box-shadow: none;
            }
        }
    </style>
</head>
<body>
    <!-- Tombol cetak dihilangkan karena print akan dilakukan otomatis -->

    <div class="invoice-container">
        <div class="invoice-header">
            <h1>INVOICE PENGAJUAN</h1>
            <p>{{ $pengajuan->no_surat }}</p>
        </div>

        <div class="invoice-details">
            <div class="invoice-details-left">
                <h3>Pengaju:</h3>
                <p><strong>{{ $pengajuan->nama_karyawan }}</strong></p>
                <p>Divisi: {{ $pengajuan->divisi }}</p>
                <p>Plot: {{ $pengajuan->plot }}</p>
                <p>Keperluan: {{ $pengajuan->keperluan_beban }}</p>
            </div>
            <div class="invoice-details-right">
                <h3>Informasi Invoice:</h3>
                <p>Tanggal Pengajuan: {{ Carbon\Carbon::parse($pengajuan->tgl_pengajuan)->format('d-m-Y') }}</p>
                <p>Tanggal Cetak: {{ $invoiceDate }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th width="5%">No</th>
                    <th width="40%">Nama Barang</th>
                    <th width="10%">Qty</th>
                    <th width="20%">Harga</th>
                    <th width="25%">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuan->detailPengajuans as $index => $detail)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $detail->namaBarang->nama_barang }}</td>
                    <td class="text-center">{{ $detail->qty }}</td>
                    <td class="text-right">Rp {{ number_format($detail->harga, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-right">Total</th>
                    <th class="text-right">Rp {{ number_format($totalAmount, 0, ',', '.') }}</th>
                </tr>
            </tfoot>
        </table>

        <div class="signature-area">
            <div class="signature-box">
                <p>Diajukan oleh:</p>
                <div class="signature-line"></div>
                <p><strong>{{ $pengajuan->nama_karyawan }}</strong></p>
                <p>{{ $pengajuan->divisi }}</p>
            </div>
            <div class="signature-box">
                <p>Disetujui oleh:</p>
                <div class="signature-line"></div>
                <p><strong>_____________________</strong></p>
                <p>Direktur/Manajer Keuangan</p>
            </div>
        </div>

        <div class="footer">
            <p>Invoice ini dicetak otomatis dan merupakan bukti resmi pengajuan barang.</p>
            <p>© {{ date('Y') }} - Sistem Pengajuan Barang</p>
        </div>
    </div>

    <script>
        // Script untuk otomatis menampilkan dialog print saat halaman selesai dimuat
        document.addEventListener('DOMContentLoaded', function() {
            // Berikan sedikit waktu agar halaman di-render dengan sempurna
            setTimeout(function() {
                window.print();
            }, 500);
        });
    </script>
</body>
</html>