<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use Illuminate\Http\Request;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Exception;

class PrinterController extends Controller
{
    public function printBill($id)
    {
        try {
            $pesanan = Pesanan::with(['detailPesanan.produk', 'detailPesanan.produkVarian'])->findOrFail($id);

            // Ganti nama printer sesuai dengan share name di Windows
            // Caranya: Control Panel -> Devices and Printers -> Klik Kanan Printer -> Printer Properties -> Sharing -> Share Name
            $connector = new WindowsPrintConnector("POS-58"); 
            $printer = new Printer($connector);

            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("UMKM COFFEE\n");
            $printer->text("Jl. Contoh No. 123, Indonesia\n");
            $printer->text("--------------------------------\n");

            // Info Pesanan
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("No: " . $pesanan->nomor_antrian . "\n");
            $printer->text("Tgl: " . $pesanan->created_at->format('d/m/Y H:i') . "\n");
            $printer->text("Plg: " . ($pesanan->nama_pelanggan ?: 'Umum') . "\n");
            $printer->text("--------------------------------\n");

            // Item
            foreach ($pesanan->detailPesanan as $detail) {
                // Nama Produk
                $nama = $detail->produk->nama_produk;
                if($detail->produkVarian) {
                    $nama .= " (" . $detail->produkVarian->nama_varian . ")";
                }
                $printer->text($nama . "\n");
                
                // Qty x Harga
                $line = $detail->jumlah . "x " . number_format($detail->harga_satuan, 0, ',', '.') . " = " . number_format($detail->subtotal, 0, ',', '.');
                $printer->text($line . "\n");

                // Topping
                if (!empty($detail->toppings)) {
                    foreach ($detail->toppings as $topping) {
                        $printer->text("  + " . $topping['name'] . "\n");
                    }
                }
            }

            $printer->text("--------------------------------\n");

            // Total
            $printer->setJustification(Printer::JUSTIFY_RIGHT);
            $printer->text("TOTAL: Rp " . number_format($pesanan->total_bayar, 0, ',', '.') . "\n");
            
            if ($pesanan->nominal_bayar) {
                $printer->text("BAYAR: Rp " . number_format($pesanan->nominal_bayar, 0, ',', '.') . "\n");
                $printer->text("KEMBALI: Rp " . number_format($pesanan->kembalian, 0, ',', '.') . "\n");
            }
            
            // Footer
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("--------------------------------\n");
            $printer->text("Terima Kasih\n");
            $printer->text("Silakan Datang Kembali\n");
            $printer->feed(3);
            $printer->cut();
            $printer->close();

            return response()->json(['success' => true, 'message' => 'Struk berhasil dicetak']);

        } catch (Exception $e) {
            return response()->json(['success' => false, 'message' => 'Gagal mencetak: ' . $e->getMessage()], 500);
        }
    }
}
