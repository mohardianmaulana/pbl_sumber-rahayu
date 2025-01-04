<?php

namespace App\Charts;

use App\Models\Barang;
use ArielMejiaDev\LarapexCharts\LarapexChart;
use Illuminate\Support\Facades\DB;


class JumlahBarangChart
{
    protected $chart;

    public function __construct(LarapexChart $chart)
    {
        $this->chart = $chart;
    }

    public function buildDonutChart(): \ArielMejiaDev\LarapexCharts\DonutChart
    {
        $jumlahBarang = Barang::all();

        $barang = [];
        $label = [];

        foreach ($jumlahBarang as $barangItem) {
            $barang[] = $barangItem->jumlah;
            $label[] = $barangItem->nama; // Ganti 'nama' dengan kolom yang sesuai dari tabel 'Barang'
        }

        return $this->chart->donutChart()
            ->setTitle('Stok Barang')
            ->setSubtitle(date('M'))
            ->setWidth(300)
            ->setHeight(300)
            ->addData($barang)
            ->setLabels($label);
    }

    public function buildBarChart(): \ArielMejiaDev\LarapexCharts\BarChart
    {
        // Dapatkan bulan dan tahun saat ini
        $currentMonth = date('m');
        $currentYear = date('Y');

        // Query data dari tabel pivot dengan join ke tabel barang
        $data = DB::table('barang_penjualan')
            ->select('barang.nama', DB::raw('SUM(barang_penjualan.jumlah) as total_jumlah'))
            ->join('barang', 'barang.id', '=', 'barang_penjualan.barang_id')
            ->whereMonth('barang_penjualan.created_at', $currentMonth) // Filter berdasarkan bulan saat ini
            ->whereYear('barang_penjualan.created_at', $currentYear)  // Filter berdasarkan tahun saat ini
            ->groupBy('barang.nama')
            ->get();

        $barang = [];
        $label = [];

        // Loop data untuk memisahkan nama barang dan jumlah total
        foreach ($data as $item) {
            $barang[] = $item->total_jumlah;
            $label[] = $item->nama;
        }

        // Return chart dengan data yang telah difilter
        return $this->chart->barChart()
            ->setTitle('Jumlah Barang Terjual per Item')
            ->setSubtitle('Penjualan Bulan ' . date('F Y'))
            ->setWidth(300)
            ->setHeight(300)
            ->addData('Jumlah', $barang)
            ->setXAxis($label);
    }
}
