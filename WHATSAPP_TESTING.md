# Panduan Pengujian Notifikasi WhatsApp

Dokumen ini berisi langkah-langkah untuk menguji integrasi notifikasi WhatsApp di sistem pengajuan menggunakan Fonnte API.

## Persiapan Awal

1. Pastikan Anda telah mendaftar dan memiliki akun di [Fonnte WhatsApp Gateway](https://fonnte.com).
2. Dapatkan API Key dari dashboard Fonnte.
3. Tambahkan konfigurasi berikut ke file `.env` aplikasi:
```
FONNTE_API_KEY=your_fonnte_api_key_here
FONNTE_BASE_URL=https://api.fonnte.com
```

## Pengujian Notifikasi Pengajuan Baru

1. Login ke sistem dengan user yang memiliki role `keuangan`.
2. Buat pengajuan baru dan isi semua detail yang diperlukan.
3. Tambahkan beberapa item pada pengajuan.
4. Pastikan user direktur memiliki nomor WhatsApp yang valid di field `no_wa` (format: 628xxx).
5. Setelah pengajuan berhasil dibuat, direktur seharusnya menerima notifikasi WhatsApp dengan detail pengajuan.
6. Periksa log aplikasi untuk memastikan notifikasi berhasil dikirim: `storage/logs/laravel.log`.

## Pengujian Notifikasi Approval/Rejection

1. Login ke sistem dengan user yang memiliki role `direktur`.
2. Buka daftar pengajuan dan pilih pengajuan yang akan disetujui/ditolak.
3. Setujui/tolak item pengajuan satu per satu atau menggunakan tombol "Setujui Semua".
4. Pastikan user keuangan memiliki nomor WhatsApp yang valid di field `no_wa` (format: 628xxx).
5. Setelah item disetujui/ditolak, user keuangan seharusnya menerima notifikasi WhatsApp dengan detail status.
6. Periksa log aplikasi untuk memastikan notifikasi berhasil dikirim.

## Troubleshooting

### Notifikasi Tidak Terkirim

1. Periksa log aplikasi untuk error atau warning terkait pengiriman WhatsApp.
2. Pastikan API Key Fonnte sudah benar dan aktif.
3. Pastikan format nomor WhatsApp sudah benar (628xxx tanpa +).
4. Periksa kuota dan saldo di akun Fonnte Anda.

### Response Status Error

Jika Anda melihat status error dari API Fonnte, berikut beberapa kemungkinan penyebab:
- API Key tidak valid atau expired
- Format nomor telepon tidak valid
- Rate limit terlampaui
- Server Fonnte sedang mengalami gangguan

## Monitoring dan Logging

Semua aktivitas pengiriman notifikasi WhatsApp dicatat dalam log aplikasi. Anda dapat memantau log ini untuk:
- Memastikan notifikasi terkirim dengan benar
- Mendeteksi masalah atau error
- Melihat detail response dari Fonnte API

Log dapat ditemukan di: `storage/logs/laravel.log`