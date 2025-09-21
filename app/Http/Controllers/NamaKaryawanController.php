<?php

namespace App\Http\Controllers;

use App\Models\NamaKaryawan;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class NamaKaryawanController extends Controller
{
    public function index()
    {
        $karyawan = NamaKaryawan::all();

        // Check user role and return the appropriate view
        if (auth()->user()->role === 'admin') {
            return view('admin.database.db_karyawan.index', compact('karyawan'));
        } else if (auth()->user()->role === 'keuangan') {
            return view('keuangan.database.db_karyawan.index', compact('karyawan'));
        }

        // Default fallback (optional)
        abort(403, 'Unauthorized action.');
    }

    public function sync(){
        NamaKaryawan::truncate();

        // HIT API
        try {
            $client = new Client();

            $url = "https://script.google.com/macros/s/AKfycbxXSEaalSu-72pxuERnrkdmiPQrkTFBEsBZcWv8_r2a-qMcJW9j-XXnWBgKS8kU_qQl/exec";

            $response = $client->request('GET', $url, [
                'verify'  => false,
            ]);

            $data = json_decode($response->getBody());
            $karyawans = collect($data); // Change to collection
        } catch (\Throwable $th) {
            return redirect()->back()->with('delete', 'Data Karyawan Gagal di Syncronize!');
        }

        // INSERT KE DATABASE
        foreach ($karyawans as $ky) {

            // CEK APAKAH DATA TELAH ADA
            $itemInBarang = NamaKaryawan::where('nama_karyawan', $ky->nama_karyawan)->first();
            if (!$itemInBarang && $ky->nama_karyawan != '') {
                NamaKaryawan::create([
                    'nama_karyawan' => $ky->nama_karyawan,
                    'divisi' => $ky->divisi,
                ]);
            }
        }
        return redirect()->back()->with('success', 'Data Karyawan Berhasil di Syncronize!');
    }
}
