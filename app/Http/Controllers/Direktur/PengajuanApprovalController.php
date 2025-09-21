<?php

namespace App\Http\Controllers\Direktur;

use App\Models\Pengajuan;
use App\Models\DetailPengajuan;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class PengajuanApprovalController extends Controller
{
    /**
     * Display a listing of the pengajuan.
     */
    public function index(Request $request)
    {
        $keyword = $request->query('search');
        $status = $request->query('status');
        
        $pengajuans = Pengajuan::with(['detailPengajuans' => function($query) use ($status) {
            if ($status) {
                $query->where('status_persetujuan', $status);
            }
        }])
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
        
        return view('direktur.pengajuan.index', compact('pengajuans', 'keyword', 'status'));
    }

    /**
     * Display the specified pengajuan with its details.
     */
    public function show(Pengajuan $pengajuan)
    {
        $pengajuan->load('detailPengajuans');
        
        // Menghitung statistik item
        $stats = [
            'total_items' => $pengajuan->detailPengajuans->count(),
            'approved' => $pengajuan->detailPengajuans->where('status_persetujuan', 'approved')->count(),
            'rejected' => $pengajuan->detailPengajuans->where('status_persetujuan', 'rejected')->count(),
            'pending' => $pengajuan->detailPengajuans->where('status_persetujuan', '!=', 'approved')
                        ->where('status_persetujuan', '!=', 'rejected')->count(),
        ];
        
        return view('direktur.pengajuan.show', compact('pengajuan', 'stats'));
    }
    
    /**
     * Update the status of an item.
     */
    public function updateStatus(Request $request, Pengajuan $pengajuan, DetailPengajuan $detail)
    {
        $validated = $request->validate([
            'status_persetujuan' => 'required|in:approved,rejected,pending',
            'keterangan_revisi' => 'nullable|string|max:255',
        ]);
        
        // Pastikan detail milik pengajuan yang benar
        if ($detail->id_pengajuan != $pengajuan->id) {
            return back()->with('error', 'Item tidak ditemukan dalam pengajuan ini!');
        }
        
        // Update status persetujuan
        $detail->update([
            'status_persetujuan' => $validated['status_persetujuan'],
            'keterangan_revisi' => $validated['keterangan_revisi'] ?? null,
        ]);
        
        // Logging
        $statusText = [
            'approved' => 'menyetujui',
            'rejected' => 'menolak',
            'pending' => 'menunda'
        ][$validated['status_persetujuan']];
        
        // Log ke model AuditLog
        $user = Auth::user();
        AuditLog::create([
            'user_id' => $user->id,
            'user_email' => $user->email,
            'user_role' => $user->role,
            'action' => "Persetujuan Item: " . ucfirst($validated['status_persetujuan']),
            'controller' => 'PengajuanApprovalController',
            'route' => 'direktur.pengajuan.update-status',
            'method' => 'PATCH',
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status_code' => 200,
            'request_data' => [
                'pengajuan_no' => $pengajuan->no_surat,
                'item_name' => $detail->nama_barang,
                'status' => $validated['status_persetujuan'],
                'keterangan' => $validated['keterangan_revisi'] ?? '-'
            ],
            'performed_at' => now()
        ]);
        
        return back()->with('success', "Status item berhasil diubah menjadi " . ucfirst($validated['status_persetujuan']));
    }
    
    /**
     * Approve all items in a pengajuan.
     */
    public function approveAll(Request $request, Pengajuan $pengajuan)
    {
        try {
            $pengajuan->detailPengajuans()
                ->where('status_persetujuan', '!=', 'approved')
                ->update([
                    'status_persetujuan' => 'approved',
                    'keterangan_revisi' => null
                ]);
                
            // Log ke model AuditLog
            $user = Auth::user();
            AuditLog::create([
                'user_id' => $user->id,
                'user_email' => $user->email,
                'user_role' => $user->role,
                'action' => "Persetujuan Semua Item",
                'controller' => 'PengajuanApprovalController',
                'route' => 'direktur.pengajuan.approve-all',
                'method' => 'PATCH',
                'url' => request()->fullUrl(),
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent(),
                'status_code' => 200,
                'request_data' => [
                    'pengajuan_no' => $pengajuan->no_surat,
                    'action' => 'approve_all'
                ],
                'performed_at' => now()
            ]);
            
            return back()->with('success', 'Semua item berhasil disetujui');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyetujui semua item: ' . $e->getMessage());
        }
    }
}