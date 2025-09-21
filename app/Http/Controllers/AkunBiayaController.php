<?php

namespace App\Http\Controllers;

use App\Models\AkunBiaya;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class AkunBiayaController extends Controller
{
    public function index()
    {
        $akunPlot = AkunBiaya::all();

        // Check user role and return the appropriate view
        if (auth()->user()->role === 'admin') {
            return view('admin.database.db_plot.index', compact('akunPlot'));
        } else if (auth()->user()->role === 'keuangan') {
            return view('keuangan.database.db_plot.index', compact('akunPlot'));
        }

        // Default fallback (optional)
        abort(403, 'Unauthorized action.');
    }

    public function sync(){
        AkunBiaya::truncate();

        // HIT API
        try {
            $client = new Client();

            $url = "https://script.google.com/macros/s/AKfycbz6RifzWeNgD0bOUYwkckyo1zcmfKNFpn9mu_O_KapzcQUBoYaUqxju2S73R32l6DL7/exec";

            $response = $client->request('GET', $url, [
                'verify'  => false,
            ]);

            $data = json_decode($response->getBody());
            $akun = collect($data); // Change to collection
        } catch (\Throwable $th) {
            return redirect()->back()->with('delete', 'Data Akun Biaya Gagal di Syncronize!');
        }

        // INSERT KE DATABASE
        foreach ($akun as $a) {

            // CEK APAKAH DATA TELAH ADA
            $itemInBarang = AkunBiaya::where('kode', $a->kode)->first();
            if (!$itemInBarang && $a->kode != '') {
                AkunBiaya::create([
                    'sub_plot' => $a->sub_plot,
                    'keterangan_pajak' => $a->keterangan_pajak,
                    'akun_keuangan' => $a->akun_keuangan,
                    'keperluan_beban' => $a->keperluan_beban,
                    'kode' => $a->kode,
                    'plot_pengeluaran' => $a->plot_pengeluaran,
                    'jenis_transaksi' => $a->jenis_transaksi,
                    'plot' => $a->budget_plot,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Data Akun Biaya Berhasil di Syncronize!');
    }
}
