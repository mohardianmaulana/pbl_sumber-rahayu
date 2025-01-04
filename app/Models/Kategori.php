<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class Kategori extends Model
{
    use HasFactory;
    protected $table = 'kategori';
    protected $fillable = ['nama_kategori', 'gambar_kategori', 'status'];
    protected $primaryKey = 'id'; // Jika primary key tidak bernama 'id', sesuaikan dengan nama yang benar

    // Relasi Kategori ke Barang (One to Many)
    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori_id');
    }

    public static function pulihkan($id)
    {
        $kategori = Kategori::find($id);
        if ($kategori) {
            $kategori->status = 1;
            $kategori->save();
        }
        return $kategori;
    }

    public static function arsipkan($id)
    {
        $kategori = Kategori::find($id);
        if ($kategori) {
            $kategori->status = 0;
            $kategori->save();
        }
        return $kategori;
    }

    public static function tambahKategori($request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'gambar_kategori' => 'required',
        ], [
            'nama_kategori.required'=>'Nama Barang wajib diisi',
            'gambar_kategori.required'=>'Gambar Kategori wajib diisi'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                             ->withErrors($validator)
                             ->withInput();
        }
        
        $kategori = Kategori::create ([
            'nama_kategori'=>$request->nama_kategori,
            'gambar_kategori'=>$request->gambar_kategori,
            'status'=> 1,
        ]);
        return $kategori;
    }

    public static function editKategori($id)
    {
        return Kategori::where('id', $id)->first();
    }

    public static function updateKategori($id, $data)
    {
        // Update data kategori
        Kategori::where('id', $id)->update($data);

        // Hapus persetujuan yang sesuai
        $userId = Auth::id();
        Persetujuan::where('kategori_id', $id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', 'update')
            ->where('namaTabel', 'Kategori')
            ->delete();
    }
}
