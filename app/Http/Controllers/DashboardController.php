<?php

namespace App\Http\Controllers;

use App\Produk;
use App\Transaksi;
use App\BundlingHasil;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard dengan statistik lengkap
     */
    public function index()
    {
        // Hitung statistik
        $totalProduk = Produk::count();
        $totalTransaksi = Transaksi::where('status', 'selesai')->count();
        $totalBundling = BundlingHasil::count();
        $totalPenjualan = Transaksi::where('status', 'selesai')->sum('harga_akhir');

        // Data untuk chart
        $transaksiPerHari = $this->getTransaksiPerHari();
        $kategoriBestSeller = $this->getKategoriBestSeller();
        $produkBestSeller = $this->getProdukBestSeller();
        $bundlingTerbaik = BundlingHasil::orderBy('support', 'DESC')->limit(5)->get();

        return view('dashboard.index', [
            'totalProduk' => $totalProduk,
            'totalTransaksi' => $totalTransaksi,
            'totalBundling' => $totalBundling,
            'totalPenjualan' => $totalPenjualan,
            'transaksiPerHari' => $transaksiPerHari,
            'kategoriBestSeller' => $kategoriBestSeller,
            'produkBestSeller' => $produkBestSeller,
            'bundlingTerbaik' => $bundlingTerbaik
        ]);
    }

    /**
     * Hitung transaksi per hari (7 hari terakhir)
     */
    protected function getTransaksiPerHari()
    {
        $data = [];
        $labels = [];

        for ($i = 6; $i >= 0; $i--) {
            $date = \Carbon\Carbon::now()->subDays($i);
            $labels[] = $date->format('D');
            
            $count = Transaksi::where('status', 'selesai')
                ->whereDate('tanggal_transaksi', $date->toDateString())
                ->count();
            
            $data[] = $count;
        }

        return ['labels' => $labels, 'data' => $data];
    }

    /**
     * Hitung kategori best seller
     */
    protected function getKategoriBestSeller()
    {
        $data = Produk::select('kategori')
            ->selectRaw('COUNT(detail_transaksis.id) as total_terjual')
            ->join('detail_transaksis', 'produks.id', '=', 'detail_transaksis.produk_id')
            ->groupBy('kategori')
            ->orderBy('total_terjual', 'DESC')
            ->limit(5)
            ->get();

        return ['labels' => $data->pluck('kategori')->toArray(), 'data' => $data->pluck('total_terjual')->toArray()];
    }

    /**
     * Hitung produk best seller
     */
    protected function getProdukBestSeller()
    {
        $data = Produk::select('nama_produk')
            ->selectRaw('COUNT(detail_transaksis.id) as total_terjual')
            ->join('detail_transaksis', 'produks.id', '=', 'detail_transaksis.produk_id')
            ->groupBy('nama_produk')
            ->orderBy('total_terjual', 'DESC')
            ->limit(5)
            ->get();

        return ['labels' => $data->pluck('nama_produk')->toArray(), 'data' => $data->pluck('total_terjual')->toArray()];
    }
}
