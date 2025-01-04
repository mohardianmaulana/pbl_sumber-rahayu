<?php

namespace App\Http\Controllers;

use App\Charts\JumlahBarangChart;
use App\Models\Barang;
use App\Models\Pembelian;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(JumlahBarangChart $jumlahBarangChart)
    {
        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Calculate total sales revenue for the current month
        $totalPenjualan = DB::table('penjualan')
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('total_harga');

        // Calculate total purchase cost for the current month
        $totalPembelian = DB::table('pembelian')
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('total_harga');

        // Calculate the profit
        $keuntungan = $totalPenjualan - $totalPembelian;
        // Hitung jumlah total dari total_harga
        $totalPembelianBulanIni = Pembelian::whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
                                        ->sum('total_harga');
        $totalPenjualanBulanIni = Penjualan::whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
                                        ->sum('total_harga');
        $barang = Barang::all();
        $donutChart = $jumlahBarangChart->buildDonutChart(); // Menampilkan chart donat
        $barChart = $jumlahBarangChart->buildBarChart();
        return view('dashboard', compact('barang', 'totalPembelianBulanIni', 'totalPenjualanBulanIni', 'keuntungan', 'donutChart', 'barChart'));
    }
    public function kode()
    {
        return view('profile');
    }

    public function keuntungan()
    {
        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        // Calculate total sales revenue for the current month
        $totalPenjualan = DB::table('penjualan')
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('total_harga');

        // Calculate total purchase cost for the current month
        $totalPembelian = DB::table('pembelian')
            ->whereBetween('tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->sum('total_harga');

        // Calculate the profit
        $keuntungan = $totalPenjualan - $totalPembelian;
    }
}
