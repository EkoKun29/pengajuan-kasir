<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FonnteWhatsAppService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.fonnte.api_key');
        $this->baseUrl = config('services.fonnte.base_url', 'https://api.fonnte.com');
    }

    /**
     * Mengirim pesan WhatsApp
     * 
     * @param string $target Nomor telepon penerima format 628xxx
     * @param string $message Pesan yang akan dikirim
     * @return array Respon dari API
     */
    public function sendMessage($target, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => $this->apiKey,
            ])->post($this->baseUrl . '/send', [
                'target' => $target,
                'message' => $message,
            ]);

            $result = $response->json();
            
            // Log hasil untuk debugging
            Log::info('Fonnte WhatsApp API Response', [
                'target' => $target,
                'status' => $result['status'] ?? 'unknown',
                'response' => $result
            ]);
            
            return $result;
        } catch (\Exception $e) {
            Log::error('Fonnte WhatsApp API Error', [
                'target' => $target,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return [
                'status' => false,
                'reason' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Mengirim notifikasi pengajuan ke direktur
     * 
     * @param string $directorPhone Nomor telepon direktur
     * @param string $senderName Nama pengirim pengajuan
     * @param string $referenceNumber Nomor surat pengajuan
     * @param string $divisi Divisi pengirim
     * @param string $total Total pengajuan
     * @return array
     */
    public function sendNewSubmissionNotification($directorPhone, $senderName, $referenceNumber, $divisi, $total)
    {
        $formattedTotal = number_format($total, 0, ',', '.');
        
        $message = "🔔 *PENGAJUAN BARU*\n\n"
            . "Anda memiliki pengajuan baru yang memerlukan persetujuan:\n\n"
            . "📝 *No. Surat:* $referenceNumber\n"
            . "👤 *Dari:* $senderName\n"
            . "🏢 *Divisi:* $divisi\n"
            . "💰 *Total:* Rp $formattedTotal\n\n"
            . "Silakan login ke sistem untuk memeriksa dan menyetujui pengajuan ini.\n"
            . "Terima kasih 🙏";
        
        return $this->sendMessage($directorPhone, $message);
    }
    
    /**
     * Mengirim notifikasi status pengajuan ke keuangan
     * 
     * @param string $receiverPhone Nomor telepon staf keuangan
     * @param string $referenceNumber Nomor surat pengajuan
     * @param string $status Status pengajuan (approved/rejected)
     * @param string $note Catatan (opsional)
     * @return array
     */
    public function sendSubmissionStatusNotification($receiverPhone, $referenceNumber, $status, $note = null)
    {
        // Status emoji
        $emoji = $status === 'approved' ? '✅' : '❌';
        $statusText = $status === 'approved' ? 'DISETUJUI' : 'DITOLAK';
        
        $message = "$emoji *PENGAJUAN $statusText*\n\n"
            . "Pengajuan dengan nomor surat berikut telah $statusText oleh direktur:\n\n"
            . "📝 *No. Surat:* $referenceNumber\n";
        
        // Tambahkan catatan jika ada
        if ($note) {
            $message .= "📌 *Catatan:* $note\n\n";
        } else {
            $message .= "\n";
        }
        
        $message .= "Silakan login ke sistem untuk melihat detail selengkapnya.\n"
            . "Terima kasih 🙏";
        
        return $this->sendMessage($receiverPhone, $message);
    }
}