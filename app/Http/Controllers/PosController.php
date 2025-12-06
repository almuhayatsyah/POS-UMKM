<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\BahanBaku;
use App\Models\Topping;
use App\Models\Resep;
use App\Models\ResepTopping;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PosController extends Controller
{
    public function index()
    {
        $produk = Produk::with('varian')->where('tersedia', true)->get();
        $toppings = Topping::all();
        // Get pending orders (Unpaid)
        $pendingOrders = Pesanan::where('status_pembayaran', 'BELUM_LUNAS')
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('pos.index', compact('produk', 'toppings', 'pendingOrders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.id' => 'exists:produk,id',
            'cart.*.qty' => 'integer|min:1',
            'nama_pelanggan' => 'nullable|string|max:100',
        ]);

        try {
            DB::beginTransaction();

            // Calculate Total
            $totalBayar = 0;
            foreach ($request->cart as $item) {
                $itemTotal = $item['price'] * $item['qty'];
                $totalBayar += $itemTotal;
            }

            // Generate Queue Number
            $today = Carbon::now()->format('Y-m-d');
            $lastOrder = Pesanan::whereDate('created_at', $today)->latest()->first();
            $queueNumber = 1;
            if ($lastOrder) {
                $lastQueue = explode('-', $lastOrder->nomor_antrian);
                $queueNumber = (int)end($lastQueue) + 1;
            }
            $formattedQueue = 'A-' . str_pad($queueNumber, 3, '0', STR_PAD_LEFT);

            // Create Order - Status: BELUM_LUNAS
            $pesanan = Pesanan::create([
                'nomor_antrian' => $formattedQueue,
                'nama_pelanggan' => $request->nama_pelanggan ?? 'Pelanggan',
                'total_bayar' => $totalBayar,
                'status_pembayaran' => 'BELUM_LUNAS',
                'status_pesanan' => 'DIPROSES',
            ]);

            // Process Items
            foreach ($request->cart as $item) {
                $produk = Produk::find($item['id']);
                if (!$produk) continue;

                $variantId = $item['variant_id'] ?? null;
                $toppings = $item['toppings'] ?? []; 

                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id,
                    'produk_id' => $produk->id,
                    'produk_varian_id' => $variantId,
                    'jumlah' => $item['qty'],
                    'subtotal_item' => $item['price'] * $item['qty'],
                    'toppings' => $toppings,
                ]);

                // Deduct Stock Logic (Same as before)
                $resepQuery = Resep::where('produk_id', $produk->id);
                if ($variantId) {
                    $resepQuery->where('produk_varian_id', $variantId);
                } else {
                    $resepQuery->whereNull('produk_varian_id');
                }
                $reseps = $resepQuery->get();

                foreach ($reseps as $resep) {
                    $bahan = BahanBaku::find($resep->bahan_baku_id);
                    if ($bahan) {
                        $bahan->stok_saat_ini -= ($resep->jumlah_bahan * $item['qty']);
                        $bahan->save();
                    }
                }

                foreach ($toppings as $toppingData) {
                    $toppingId = $toppingData['id'];
                    $resepToppings = ResepTopping::where('topping_id', $toppingId)->get();
                    foreach ($resepToppings as $rt) {
                        $bahan = BahanBaku::find($rt->bahan_baku_id);
                        if ($bahan) {
                            $bahan->stok_saat_ini -= ($rt->jumlah * $item['qty']);
                            $bahan->save();
                        }
                    }
                }
            }

            DB::commit();

            return response()->json([
                'success' => true, 
                'message' => 'Pesanan masuk ke dapur',
                'queue' => $formattedQueue
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false, 
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }

    public function pay($id)
    {
        try {
            $pesanan = Pesanan::find($id);
            if (!$pesanan) {
                return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
            }

            $pesanan->status_pembayaran = 'LUNAS';
            $pesanan->status_pesanan = 'SELESAI'; // Assuming payment closes the loop
            $pesanan->save();

            return response()->json(['success' => true, 'message' => 'Pembayaran berhasil!']);

        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function kitchen()
    {
        // Show orders that are NOT Finished (DIPROSES or SIAP_SAJI)
        $activeOrders = Pesanan::whereIn('status_pesanan', ['DIPROSES', 'SIAP_SAJI'])
            ->with('detailPesanan.produk', 'detailPesanan.produkVarian')
            ->orderBy('created_at', 'asc')
            ->get();
            
        return view('pos.kitchen', compact('activeOrders'));
    }

    public function updateStatus($id, Request $request)
    {
        $pesanan = Pesanan::find($id);
        if ($pesanan) {
            $status = $request->input('status'); // 'SIAP_SAJI' or 'SELESAI'
            if ($status) {
                $pesanan->status_pesanan = $status;
                $pesanan->save();
                return redirect()->back()->with('success', 'Status pesanan diperbarui.');
            }
        }
        return redirect()->back()->with('error', 'Gagal memperbarui status.');
    }
}
