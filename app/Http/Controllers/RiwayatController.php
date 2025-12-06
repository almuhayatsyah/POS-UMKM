<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        $riwayat = Pesanan::with('detailPesanan')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
                    
        return view('riwayat.index', compact('riwayat'));
    }

    public function show($id)
    {
        $pesanan = Pesanan::with(['detailPesanan.produk'])->findOrFail($id);
        return view('riwayat.show', compact('pesanan'));
    }

    public function destroy($id)
    {
        $pesanan = Pesanan::findOrFail($id);
        
        // Optional: Restore stock if needed, but usually history deletion doesn't restore stock in simple POS
        // If we wanted to restore stock, we would loop through details and add back to BahanBaku
        
        $pesanan->delete(); // This will cascade delete detail_pesanan if foreign key is set to CASCADE, otherwise we might need to delete details first.
        // Checking migration: 2025_12_02_195627_create_detail_pesanan_table.php usually has cascade on delete for pesanan_id?
        // Let's assume standard Laravel cascade or manual deletion if needed. 
        // For safety/simplicity in this request, we just delete the parent. 
        // If the user didn't set cascade in migration, this might fail. 
        // Let's check migration first to be sure or just delete details manually to be safe.
        
        $pesanan->detailPesanan()->delete();
        $pesanan->delete();

        return redirect()->route('riwayat.index')->with('success', 'Riwayat transaksi berhasil dihapus.');
    }
}
