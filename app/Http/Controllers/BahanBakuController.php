<?php

namespace App\Http\Controllers;

use App\Models\BahanBaku;
use Illuminate\Http\Request;

class BahanBakuController extends Controller
{
    public function index()
    {
        $bahanBaku = BahanBaku::all();
        return view('bahan_baku.index', compact('bahanBaku'));
    }

    public function create()
    {
        return view('bahan_baku.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_bahan' => 'required|unique:bahan_baku,nama_bahan|max:100',
            'stok_saat_ini' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli_terakhir' => 'nullable|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
        ]);

        BahanBaku::create($request->all());

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    public function show(BahanBaku $bahanBaku)
    {
        // Load products that use this ingredient via Resep
        $bahanBaku->load('resep.produk');
        return view('bahan_baku.show', compact('bahanBaku'));
    }

    public function edit(BahanBaku $bahanBaku)
    {
        return view('bahan_baku.edit', compact('bahanBaku'));
    }

    public function update(Request $request, BahanBaku $bahanBaku)
    {
        $request->validate([
            'nama_bahan' => 'required|max:100|unique:bahan_baku,nama_bahan,' . $bahanBaku->id,
            'stok_saat_ini' => 'required|numeric|min:0',
            'satuan' => 'required|string|max:20',
            'harga_beli_terakhir' => 'nullable|numeric|min:0',
            'stok_minimum' => 'required|numeric|min:0',
        ]);

        $bahanBaku->update($request->all());

        return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil diperbarui.');
    }

    public function destroy(BahanBaku $bahanBaku)
    {
        try {
            $bahanBaku->delete();
            return redirect()->route('bahan-baku.index')->with('success', 'Bahan baku berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('bahan-baku.index')->with('error', 'Gagal menghapus bahan baku. Mungkin sedang digunakan dalam resep.');
        }
    }
}
