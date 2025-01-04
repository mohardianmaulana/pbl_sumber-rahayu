<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['nama', 'nomor', 'alamat', 'status'];
    protected $primaryKey = 'id';

    public static function tampil()
    {
        // Mengambil data kategori yang statusnya 0
        return Supplier::where('status', 1)->get();
        
    }

    public static function tambah($request) {
        $request->validate([
            'nama' => 'required',
            'nomor' => 'required',
            'alamat' => 'required',
        ], [
            'nama.required'=>'Nama supplier wajib diisi',
            'nomor.required'=>'Nomor HP wajib diisi',
            'alamat.required'=>'Alamat wajib diisi',
        ]);
        $supplier = [
            'nama'=>$request->nama,
            'nomor'=>$request->nomor,
            'alamat'=>$request->alamat,
            'status'=> 1,

        ]; 
        
       return Supplier::create($supplier);
    }

    public static function tampilArsip()
    {
        return Supplier::where('status', 0)->get();
    }

    public static function pulih($id) {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->status = 1;
            $supplier->save();
        }
        return $supplier;
    }
    public static function arsip($id) {
        $supplier = Supplier::find($id);
        if ($supplier) {
            $supplier->status = 0;
            $supplier->save();
        }
        return $supplier;
    }

    public static function ubah($id) {
        return Supplier::where('id', $id)->first();
    }

    public static function ganti($request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nomor' => 'required',
            'alamat' => 'required',
        ], [
            'nama.required'=>'Nama supplier wajib diisi',
            'nomor.required'=>'Nomor HP wajib diisi',
            'alamat.required'=>'Alamat wajib diisi',
        ]);
        $supplier = [
            'nama'=>$request->nama,
            'nomor'=>$request->nomor,
            'alamat'=>$request->alamat,
        ];
        Supplier::where('id', $id)->update($supplier);
        $userId = auth()->id();
        Persetujuan::where('supplier_id', $id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', 'update')
            ->where('namaTabel', 'Supplier')
            ->delete();


        return [
                'status' => 'success',
                'message' => 'Berhasil melakukan update data supplier!'
            ];
    

}

}


