<?php

namespace App\Http\Controllers;

use App\Models\Topping;
use App\Models\BahanBaku;
use App\Models\ResepTopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ToppingController extends Controller
{
    public function index()
    {
        $toppings = Topping::all();
        return view('topping.index', compact('toppings'));
    }

    public function create()
    {
        $bahanBaku = BahanBaku::all();
        return view('topping.create', compact('bahanBaku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_topping' => 'required|max:100',
            'harga' => 'required|numeric|min:0',
            'resep' => 'required|array',
            'resep.*.bahan_id' => 'required|exists:bahan_baku,id',
            'resep.*.jumlah' => 'required|numeric|min:0.001',
        ]);

        try {
            DB::beginTransaction();

            $topping = Topping::create([
                'nama_topping' => $request->nama_topping,
                'harga' => $request->harga,
            ]);

            foreach ($request->resep as $resepData) {
                ResepTopping::create([
                    'topping_id' => $topping->id,
                    'bahan_baku_id' => $resepData['bahan_id'],
                    'jumlah' => $resepData['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->route('topping.index')->with('success', 'Topping berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Topping $topping)
    {
        $bahanBaku = BahanBaku::all();
        $topping->load('resepTopping');
        return view('topping.edit', compact('topping', 'bahanBaku'));
    }

    public function update(Request $request, Topping $topping)
    {
        $request->validate([
            'nama_topping' => 'required|max:100',
            'harga' => 'required|numeric|min:0',
            'resep' => 'required|array',
            'resep.*.bahan_id' => 'required|exists:bahan_baku,id',
            'resep.*.jumlah' => 'required|numeric|min:0.001',
        ]);

        try {
            DB::beginTransaction();

            $topping->update([
                'nama_topping' => $request->nama_topping,
                'harga' => $request->harga,
            ]);

            // Sync Recipe
            $topping->resepTopping()->delete();

            foreach ($request->resep as $resepData) {
                ResepTopping::create([
                    'topping_id' => $topping->id,
                    'bahan_baku_id' => $resepData['bahan_id'],
                    'jumlah' => $resepData['jumlah'],
                ]);
            }

            DB::commit();
            return redirect()->route('topping.index')->with('success', 'Topping berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Topping $topping)
    {
        $topping->delete();
        return redirect()->route('topping.index')->with('success', 'Topping berhasil dihapus.');
    }
}
