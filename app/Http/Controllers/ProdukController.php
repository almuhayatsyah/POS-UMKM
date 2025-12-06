<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\BahanBaku;
use App\Models\Resep;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Produk::all();
        return view('produk.index', compact('produk'));
    }

    public function create()
    {
        $bahanBaku = BahanBaku::all();
        return view('produk.create', compact('bahanBaku'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_produk' => 'required|max:100',
            'kategori' => 'nullable|string|max:50',
            // Validation for simple product
            'harga_jual' => 'required_without:variants|nullable|numeric|min:0',
            // Validation for variants
            'variants' => 'nullable|array',
            'variants.*.nama' => 'required_with:variants|string',
            'variants.*.harga' => 'required_with:variants|numeric|min:0',
            // Validation for recipes
            'resep' => 'nullable|array',
            'resep.*.bahan_id' => 'required|exists:bahan_baku,id',
            'resep.*.jumlah' => 'required|numeric|min:0.001',
            'resep.*.scope' => 'nullable|string', // 'base' or 'variant_index_0', etc.
        ]);

        try {
            DB::beginTransaction();

            $produk = Produk::create([
                'nama_produk' => $request->nama_produk,
                'harga_jual' => $request->harga_jual ?? 0, // 0 if has variants
                'kategori' => $request->kategori,
                'tersedia' => true,
            ]);

            $variantMap = []; // Map index to ID

            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $index => $variantData) {
                    $variant = $produk->varian()->create([
                        'nama_varian' => $variantData['nama'],
                        'harga' => $variantData['harga'],
                    ]);
                    $variantMap['variant_index_' . $index] = $variant->id;
                }
            }

            if ($request->has('resep') && is_array($request->resep)) {
                foreach ($request->resep as $resepData) {
                    $produkVarianId = null;
                    
                    if (isset($resepData['scope']) && str_starts_with($resepData['scope'], 'variant_index_')) {
                        $produkVarianId = $variantMap[$resepData['scope']] ?? null;
                    }

                    // Only create recipe if it belongs to base (and no variants exist?) 
                    // OR if it belongs to a valid variant.
                    // If product has variants, base recipe might be common ingredients? 
                    // For now, let's assume recipe is either for base OR specific variant.
                    
                    Resep::create([
                        'produk_id' => $produk->id,
                        'produk_varian_id' => $produkVarianId,
                        'bahan_baku_id' => $resepData['bahan_id'],
                        'jumlah_bahan' => $resepData['jumlah'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil ditambahkan.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function edit(Produk $produk)
    {
        $bahanBaku = BahanBaku::all();
        $produk->load(['resep', 'varian']);
        return view('produk.edit', compact('produk', 'bahanBaku'));
    }

    public function update(Request $request, Produk $produk)
    {
        $request->validate([
            'nama_produk' => 'required|max:100',
            'kategori' => 'nullable|string|max:50',
            'harga_jual' => 'required_without:variants|nullable|numeric|min:0',
            'variants' => 'nullable|array',
            'variants.*.nama' => 'required_with:variants|string',
            'variants.*.harga' => 'required_with:variants|numeric|min:0',
            'resep' => 'nullable|array',
            'resep.*.bahan_id' => 'required|exists:bahan_baku,id',
            'resep.*.jumlah' => 'required|numeric|min:0.001',
            'resep.*.scope' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();

            $produk->update([
                'nama_produk' => $request->nama_produk,
                'harga_jual' => $request->harga_jual ?? 0,
                'kategori' => $request->kategori,
            ]);

            // Handle Variants
            // Strategy: Delete all existing and recreate? Or smart sync?
            // For simplicity in this iteration: Delete all variants and recreate. 
            // Warning: This changes IDs, might affect history if not careful. 
            // But history uses foreign keys with 'set null' or we just keep history as text.
            // Better: Keep existing if possible, but mapping UI index to DB ID is hard without hidden inputs.
            // Let's go with: Delete all variants and recipes, then recreate. 
            // This is "destructive" update but safe for data integrity of the current state.
            
            $produk->varian()->delete(); 
            $produk->resep()->delete();

            $variantMap = [];

            if ($request->has('variants') && is_array($request->variants)) {
                foreach ($request->variants as $index => $variantData) {
                    $variant = $produk->varian()->create([
                        'nama_varian' => $variantData['nama'],
                        'harga' => $variantData['harga'],
                    ]);
                    $variantMap['variant_index_' . $index] = $variant->id;
                }
            }

            if ($request->has('resep') && is_array($request->resep)) {
                foreach ($request->resep as $resepData) {
                    $produkVarianId = null;
                    if (isset($resepData['scope']) && str_starts_with($resepData['scope'], 'variant_index_')) {
                        $produkVarianId = $variantMap[$resepData['scope']] ?? null;
                    }

                    Resep::create([
                        'produk_id' => $produk->id,
                        'produk_varian_id' => $produkVarianId,
                        'bahan_baku_id' => $resepData['bahan_id'],
                        'jumlah_bahan' => $resepData['jumlah'],
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('produk.index')->with('success', 'Produk berhasil diperbarui.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy(Produk $produk)
    {
        $produk->delete();
        return redirect()->route('produk.index')->with('success', 'Produk berhasil dihapus.');
    }
}
