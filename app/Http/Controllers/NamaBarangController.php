<?php

namespace App\Http\Controllers;

use App\Models\NamaBarang;
use Illuminate\Http\Request;

class NamaBarangController extends Controller
{
     public function index(Request $request)
    {
        //search and pagination
        $keyword = $request->query('search');

        $barangs = NamaBarang::query()
            ->when($keyword, function ($q) use ($keyword) {
                $q->where(function ($q2) use ($keyword) {
                    $q2->where('nama_barang',  'like', "%{$keyword}%");
                });
            })
            ->paginate(10)
            ->appends(['search' => $keyword]);
            
        return view('keuangan.barang.index', compact('barangs', 'keyword'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string',
        ]);

        // ubah ke uppercase
        $validated['nama_barang'] = strtoupper($validated['nama_barang']);

        NamaBarang::create($validated);

        return back()->with('success', 'Barang berhasil ditambahkan!');
    }

    public function update(Request $request, NamaBarang $barang)
    {
        $validated = $request->validate([
            'nama_barang' => 'required|string',
        ]);

        $validated['nama_barang'] = strtoupper($validated['nama_barang']);

        $barang->update($validated);

        return back()->with('success', 'Barang berhasil diperbarui!');
    }

    public function destroy(NamaBarang $barang)
    {
        $barang->delete();

        return back()->with('success', 'Barang berhasil dihapus!');
    }

}
