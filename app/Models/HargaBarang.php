<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HargaBarang extends Model
{
    use HasFactory;
    protected $table = 'harga_barang';
    protected $fillable = [
        'barang_id', 'supplier_id', 'harga_beli', 'harga_jual', 'tanggal_mulai', 'tanggal_selesai'
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class);
    }

    public static function viewHarga(){
        // Membuat query join antara tabel 'harga_barang', 'barang', dan 'supplier' dan mengeksekusinya
        $hargaBarang = HargaBarang::leftJoin('barang', 'harga_barang.barang_id', '=', 'barang.id')
        ->leftJoin('supplier', 'harga_barang.supplier_id', '=', 'supplier.id')
        ->select('harga_barang.*', 'barang.nama as nama_barang', 'supplier.nama as nama_supplier')
        ->orderBy('harga_barang.tanggal_mulai', 'asc')
        ->get();

        // Menambahkan flag 'isComplete' untuk setiap item
        $hargaBarang = $hargaBarang->map(function($item) {
            $item->isComplete = !empty($item->nama_barang) && !empty($item->harga_beli) && !empty($item->harga_jual) && !empty($item->tanggal_mulai) && !empty($item->tanggal_selesai);
            return $item;
        });
        return $hargaBarang;
    }

    public static function editHarga($id){
        $hargaBarang = HargaBarang::join('barang', 'harga_barang.barang_id', '=', 'barang.id')
        ->select('harga_barang.*', 'barang.nama as nama_barang')
        ->where('harga_barang.id', $id)
        ->first();

        return $hargaBarang;
    }

    public static function updateHarga($request, $id){
         // Validasi input
         $request->validate([
            'harga_jual' => 'required|numeric',
        ]);

        // Mencari data harga barang berdasarkan ID
        $hargaBarang = HargaBarang::findOrFail($id);

        // Memperbarui data harga barang
        $hargaBarang->harga_jual = $request->input('harga_jual');
        
        // Menyimpan perubahan ke database
        $hargaBarang->save();

    }
    // Memformat tanggal mulai
    public function getFormattedTanggalMulaiAttribute()
    {
        return Carbon::parse($this->attributes['tanggal_mulai'])->format('d-m-Y');
    }

    public function getFormattedTanggalSelesaiAttribute()
    {
    return $this->attributes['tanggal_selesai'] ? Carbon::parse($this->attributes['tanggal_selesai'])->format('d-m-Y') : null;
    }
}