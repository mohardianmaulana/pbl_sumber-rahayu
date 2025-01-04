<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\Kategori;
use App\Models\Persetujuan;
use Illuminate\Support\Facades\Auth;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        // Mengambil data kategori yang statusnya 1
        $kategori = Kategori::where('status', 1)->get();

        return view('kategori.index', compact('kategori'));
    }
    // public function inde(Request $request)
    // {
    //     // Mengambil data kategori yang statusnya 1
    //     $kategori = Kategori::where('status', 1)->get();

    //     return view('kategori.index', compact('kategori'));
    // }

    public function arsip(Request $request)
    {
        // Mengambil data kategori yang statusnya 0
        $kategori = Kategori::where('status', 0)->get();

        return view('kategori.indexArsip', compact('kategori'));
    }

    public function pulihkan($id)
    {
        $kategori = Kategori::pulihkan($id);

        return redirect()->route('kategori.lama')->with('success', 'Kategori berhasil dipulihkan.');
    }

    public function arsipkan($id)
    {
        $kategori = Kategori::arsipkan($id);

        return redirect()->route('kategori')->with('success', 'Kategori berhasil diarsipkan.');
    }

    public function create()
    {
        $kategori = Kategori::all();
        return view('kategori.create', compact('kategori'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_kategori' => 'required',
            'gambar_kategori' => 'image|file|mimes:jpg,png|min:1|max:2048', // Validasi gambar
        ], [
            'nama_kategori.required' => 'Nama Kategori wajib diisi',
            'gambar_kategori.required' => 'Gambar Kategori wajib diisi',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $nm = $request->gambar_kategori;
        $namaFile = $nm->getClientOriginalName();

        $nm->move(public_path() . '/img', $namaFile);

        // Simpan data kategori ke database
        Kategori::create([
            'nama_kategori' => $request->nama_kategori,
            'gambar_kategori' => $namaFile,
            'status' => 1,
        ]);

        return redirect()->route('kategori')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function checkEdit($id)
    {
        $kategori = Kategori::find($id);
        $userId = Auth::id();
        $kerjaAksi = "update";
        $namaTabel = "Kategori";
        $data = [
            'supplier_id' => null,
            'customer_id' => null,
            'kategori_id' => $kategori->id,
            'barang_id' => null,
            'user_id' => $userId,
            'kerjaAksi' => $kerjaAksi,
            'namaTabel' => $namaTabel,
            'lagiProses' => 0,
            'kodePersetujuan' => null,
        ];

        $persetujuan = Persetujuan::where('kategori_id', $kategori->id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', $kerjaAksi)
            ->where('namaTabel', $namaTabel)
            ->first();

        $persetujuanIsiForm = $persetujuan && $persetujuan->kodePersetujuan !== null;
        $persetujuanDisetujui = $persetujuanIsiForm && $persetujuan->lagiProses == 1;

        if (!$persetujuan) {
            $persetujuan = new Persetujuan();
            $persetujuan->fill($data);
            $persetujuan->timestamps = false;
            $persetujuan->save();
            return redirect()->to('/kategori')->with('success', 'Persetujuan berhasil diajukan');
        } elseif ($persetujuanDisetujui) {
            return redirect()->route('kategori.edit', $kategori->id);
        } elseif ($persetujuanIsiForm) {
            return view('persetujuan.konfirmasi', compact('persetujuan'));
        } else {
            return redirect()->to('/kategori')->with('info', 'Tunggu persetujuan dari owner.');
        }
    }

    public function edit($id)
    {
        $kategori = Kategori::where('id', $id)->first();
        return view('kategori.edit', compact('kategori'))->with('kategori', $kategori);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_kategori' => 'required',
            'gambar_kategori' => 'nullable|image|file|mimes:jpg,png|max:2048', // Validasi gambar
        ], [
            'nama_kategori.required' => 'Nama barang Barang wajib diisi',
        ]);

        $namaFile = null;
        if ($request->hasFile('gambar_kategori')) {
            $nm = $request->gambar_kategori;
            $namaFile = $nm->getClientOriginalName();

            $nm->move(public_path() . '/img', $namaFile);
        }

        // Siapkan data kategori yang ingin diupdate
        $data = [
            'nama_kategori' => $request->nama_kategori,
            'gambar_kategori' => $namaFile,
        ];

        // Panggil method `updateKategori` dari model Kategori
        Kategori::updateKategori($id, $data);

        return redirect()->to('kategori')->with('success', 'Berhasil melakukan update data kategori');
    }
    //     public function destroy($id)
    // {
    //     // ID kategori sementara/temporary
    //     $temporaryKategoriId = '5';

    //     // Pastikan ID kategori sementara tidak sama dengan ID kategori yang akan dihapus
    //     if ($id == $temporaryKategoriId) {
    //         return redirect()->to('kategori')->with('errors', 'Kategori Temporary tidak dapat dihapus.');
    //     }

    //     // Cek apakah ada toko yang masih terhubung dengan kategori yang akan dihapus
    //     $barangCount = Barang::where('kategori_id', $id)->count();

    //     if ($barangCount > 0) {
    //         // Jika ada toko yang terhubung, perbarui kategori_id menjadi kategori sementara
    //         Barang::where('kategori_id', $id)->update(['kategori_id' => $temporaryKategoriId]);
    //     }

    //     // Hapus kategori
    //     Kategori::where('id', $id)->delete();

    //     return redirect()->to('kategori')->with('success', 'Berhasil menghapus kategori');
    // }
}
