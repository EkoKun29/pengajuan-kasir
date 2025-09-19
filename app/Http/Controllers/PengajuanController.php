<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Pengajuan;
use App\Models\DetailPengajuan;
use App\Models\NamaBarang;
use App\Models\NamaKaryawan;
use App\Models\AkunBiaya;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class PengajuanController extends Controller
{
    /**
     * Display a listing of the pengajuan.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        $status = $request->query('status');
        
        $pengajuans = Pengajuan::with('detailPengajuans')
            ->when($keyword, function($query) use ($keyword) {
                $query->where(function($q) use ($keyword) {
                    $q->where('no_surat', 'like', "%{$keyword}%")
                      ->orWhere('nama_karyawan', 'like', "%{$keyword}%")
                      ->orWhere('divisi', 'like', "%{$keyword}%");
                });
            })
            ->latest()
            ->paginate(10)
            ->appends(['search' => $keyword, 'status' => $status]);
        
        return view('keuangan.pengajuan.index', compact('pengajuans', 'keyword', 'status'));
    }

    /**
     * Show the form for creating a new pengajuan.
     */
    public function create()
    {
        // Ambil daftar nama barang untuk dropdown
        $namaBarangs = NamaBarang::all();
        
        // Ambil daftar nama karyawan untuk dropdown
        $namaKaryawans = NamaKaryawan::all();
        
        // Ambil daftar plot untuk dropdown
        $plotList = AkunBiaya::all();
        
        // Ambil semua data AkunBiaya untuk digunakan dalam JavaScript
        $akunBiayaList = AkunBiaya::select('plot', 'keperluan_beban')->whereNotNull('plot')->whereNotNull('keperluan_beban')
            ->where('plot', '!=', '')->where('keperluan_beban', '!=', '')->get();
        
        // Generate contoh nomor surat untuk ditampilkan sebagai placeholder
        $contohNomorSurat = $this->generateNoSurat();
        
        return view('keuangan.pengajuan.create', compact('namaBarangs', 'namaKaryawans', 'plotList', 'akunBiayaList', 'contohNomorSurat'));
    }

    /**
     * Store a newly created pengajuan in storage.
     */
    public function store(Request $request)
    {
        // Validate request untuk pengajuan
        $validated = $request->validate([
            'tgl_pengajuan' => 'required|date',
            'nama_karyawan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'plot' => 'required|string|max:255',
        ]);

        // Generate nomor surat
        $noSurat = $this->generateNoSurat();

        // Buat pengajuan baru dengan transaksi DB untuk memastikan konsistensi data
        DB::beginTransaction();
        try {
            // Simpan data pengajuan
            $pengajuan = Pengajuan::create([
                'tgl_pengajuan' => $validated['tgl_pengajuan'],
                'no_surat' => $noSurat,
                'nama_karyawan' => $validated['nama_karyawan'],
                'divisi' => $validated['divisi'],
                'plot' => $validated['plot'],
            ]);

            DB::commit();
            
            // Redirect ke halaman tambah detail pengajuan
            return redirect()->route('keuangan.pengajuans.detail.create', $pengajuan->id)
                             ->with('success', 'Pengajuan berhasil dibuat. Silahkan tambahkan detail barang.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    private function generateNoSurat()
    {
        $today = Carbon::now()->format('Ymd');
        $prefix = "P/{$today}/";
        
        // Dapatkan nomor surat terakhir dengan prefix yang sama
        $lastPengajuan = Pengajuan::where('no_surat', 'like', "{$prefix}%")
                                  ->orderBy('id', 'desc')
                                  ->first();
        
        if ($lastPengajuan) {
            // Ekstrak angka dari nomor surat terakhir
            $lastNumber = (int) substr($lastPengajuan->no_surat, strlen($prefix));
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }
        
        // Format nomor dengan 4 digit (misalnya 0001, 0012, dll)
        return $prefix . sprintf('%04d', $newNumber);
    }


    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('detailPengajuans');
        return view('keuangan.pengajuan.show', compact('pengajuan'));
    }

    public function edit(Pengajuan $pengajuan)
    {
        // Ambil daftar nama barang untuk dropdown
        $namaBarangs = NamaBarang::all();
        
        // Ambil daftar nama karyawan untuk dropdown
        $namaKaryawans = NamaKaryawan::all();
        
        // Ambil daftar plot untuk dropdown
        $plotList = AkunBiaya::select('plot')->distinct()->whereNotNull('plot')->where('plot', '!=', '')->get();
        
        // Ambil semua data AkunBiaya untuk digunakan dalam JavaScript
        $akunBiayaList = AkunBiaya::select('plot', 'keperluan_beban')->whereNotNull('plot')->whereNotNull('keperluan_beban')
            ->where('plot', '!=', '')->where('keperluan_beban', '!=', '')->get();
        
        return view('keuangan.pengajuan.edit', compact('pengajuan', 'namaBarangs', 'namaKaryawans', 'plotList', 'akunBiayaList'));
    }

    /**
     * Update the specified pengajuan in storage.
     */
    public function update(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'tgl_pengajuan' => 'required|date',
            'nama_karyawan' => 'required|string|max:255',
            'divisi' => 'required|string|max:255',
            'plot' => 'required|string|max:255',
        ]);

        $pengajuan->update($validated);

        return redirect()->route('keuangan.pengajuans.index')
                         ->with('success', 'Pengajuan berhasil diperbarui.');
    }

    /**
     * Remove the specified pengajuan from storage.
     */
    public function destroy(Pengajuan $pengajuan)
    {
        // Hapus semua detail pengajuan terlebih dahulu
        DB::beginTransaction();
        try {
            $pengajuan->detailPengajuans()->delete();
            $pengajuan->delete();
            
            DB::commit();
            return redirect()->route('keuangan.pengajuans.index')
                             ->with('success', 'Pengajuan berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Show form for creating detail pengajuan.
     */
    public function createDetail(Pengajuan $pengajuan)
    {
        $plotList = AkunBiaya::all();
        $namaBarangs = NamaBarang::all();
        return view('keuangan.pengajuan.create_detail', compact('pengajuan', 'namaBarangs', 'plotList'));
    }
    
    /**
     * Store a newly created detail pengajuan in storage.
     */
    public function storeDetail(Request $request, Pengajuan $pengajuan)
    {
        $validated = $request->validate([
            'items_data' => 'required|json',
        ]);
        
        // Decode JSON data
        $itemsData = json_decode($validated['items_data'], true);
        
        if (empty($itemsData)) {
            return back()->with('error', 'Tidak ada data barang yang ditambahkan.');
        }
        
        DB::beginTransaction();
        try {
            // Loop through each item and create detail pengajuan
            foreach ($itemsData as $item) {
                $pengajuan->detailPengajuans()->create([
                    'nama_barang' => $item['nama_barang'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'berpajak' => $item['berpajak'] ?? 'Tidak',
                    'keterangan_pajak' => $item['keterangan_pajak'] ?? null,
                    'keperluan_beban' => $item['keperluan_beban'] ?? null,
                    'status_persetujuan' => 'pending', // Default status
                ]);
            }
            
            // Update total pengajuan dengan jumlah semua detail
            $totalAmount = $pengajuan->detailPengajuans->sum('total');
            $pengajuan->total = $totalAmount;
            $pengajuan->save();
            
            DB::commit();
            
            return redirect()->route('keuangan.pengajuans.show', $pengajuan->id)
                            ->with('success', count($itemsData) . ' barang berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Show the form for editing the specified detail pengajuan.
     */
    public function editDetail(Pengajuan $pengajuan, DetailPengajuan $detail)
    {
        $namaBarangs = NamaBarang::all();
        return view('keuangan.pengajuan.edit_detail', compact('pengajuan', 'detail', 'namaBarangs'));
    }
    
    /**
     * Update the specified detail pengajuan in storage.
     */
    public function updateDetail(Request $request, Pengajuan $pengajuan, DetailPengajuan $detail)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|exists:nama_barangs,id',
            'qty' => 'required|numeric|min:1',
            'harga' => 'required|numeric|min:0',
            'berpajak' => 'nullable|in:Ya,Tidak',
            'keperluan_beban' => 'nullable|string|max:255',
            'keterangan_pajak' => 'nullable|string|max:255',
        ]);
        
        DB::beginTransaction();
        try {
            $detail->update([
                'nama_barang' => $validated['nama_barang'],
                'qty' => $validated['qty'],
                'harga' => $validated['harga'],
                'berpajak' => $validated['berpajak'] ?? 'Tidak',
                'keperluan_beban' => $validated['keperluan_beban'],
                'keterangan_pajak' => $validated['keterangan_pajak'],
            ]);
            
            // Update total pengajuan dengan jumlah semua detail
            $totalAmount = $pengajuan->detailPengajuans->sum('total');
            $pengajuan->total = $totalAmount;
            $pengajuan->save();
            
            DB::commit();
            
            return redirect()->route('keuangan.pengajuans.show', $pengajuan->id)
                             ->with('success', 'Detail barang berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }
    
    /**
     * Remove the specified detail pengajuan from storage.
     */
    public function destroyDetail(Pengajuan $pengajuan, DetailPengajuan $detail)
    {
        DB::beginTransaction();
        try {
            // Hapus detail
            $detail->delete();
            
            // Update total pengajuan setelah item dihapus
            $totalAmount = $pengajuan->detailPengajuans->sum('total');
            $pengajuan->total = $totalAmount;
            $pengajuan->save();
            
            DB::commit();
            return redirect()->route('keuangan.pengajuans.show', $pengajuan->id)
                             ->with('success', 'Detail barang berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    
    /**
     * Set status persetujuan for a detail pengajuan.
     */
    public function setStatus(Request $request, Pengajuan $pengajuan, DetailPengajuan $detail)
    {
        $validated = $request->validate([
            'status_persetujuan' => 'required|in:approved,rejected,pending',
            'keterangan_revisi' => 'nullable|string|max:255',
        ]);
        
        try {
            $detail->update([
                'status_persetujuan' => $validated['status_persetujuan'],
                'keterangan_revisi' => $validated['keterangan_revisi'],
            ]);
            
            return redirect()->route('keuangan.pengajuans.show', $pengajuan->id)
                             ->with('success', 'Status persetujuan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
