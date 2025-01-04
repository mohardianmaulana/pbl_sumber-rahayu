<?php

namespace App\Http\Controllers;

use App\Models\HargaBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HargaBarangController extends Controller
{
    public function index(Request $request)
    {
        $hargaBarang = HargaBarang::viewHarga();

        return view('hargaBarang.index', compact('hargaBarang'));
    }


    public function edit($id)
    {
        // Mengambil data harga barang yang ingin diedit dengan join tabel barang untuk mendapatkan nama barang
        $hargaBarang = HargaBarang::editHarga($id);

        // Mengembalikan view dengan data harga barang yang sudah diambil
        return view('hargaBarang.edit', compact('hargaBarang'));
    }

    public function update(Request $request, $id)
    {
        $hargaBarang = HargaBarang::updateHarga($request, $id);

        // Mengarahkan kembali ke halaman daftar harga dengan pesan sukses
        return redirect()->route('harga')->with('success', 'Harga barang berhasil diperbarui.');
    }
}
