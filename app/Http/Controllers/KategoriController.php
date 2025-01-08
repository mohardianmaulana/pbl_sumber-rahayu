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
            'nama_kategori' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:3|max:50',
            'gambar_kategori' => 'image|file|mimes:jpg,png|min:10|max:2048', // Validasi gambar
        ], [
            'nama_kategori.required' => 'Nama Kategori wajib diisi',
            'nama_kategori.regex' => 'Nama Kategori hanya boleh mengandung huruf dan spasi',
            'nama_kategori.min' => 'Nama Kategori harus memiliki minimal 3 karakter',
            'nama_kategori.max' => 'Nama Kategori tidak boleh lebih dari 50 karakter',

            'gambar_kategori.required' => 'Gambar Kategori wajib diisi',
            'gambar_kategori.image' => 'Gambar Kategori harus berupa gambar',
            'gambar_kategori.mimes' => 'Gambar Kategori hanya boleh memiliki format jpg atau png',
            'gambar_kategori.min' => 'Ukuran Gambar Kategori tidak boleh kurang dari 10 KB',
            'gambar_kategori.max' => 'Ukuran Gambar Kategori tidak boleh lebih dari 2048 KB',
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
    // Validasi input
    $request->validate([
        'nama_kategori' => 'required|string|regex:/^[a-zA-Z\s]+$/|min:3|max:50',
        'gambar_kategori' => 'nullable|image|file|mimes:jpg,png|min:10|max:2048', // Validasi gambar
    ], [
        'nama_kategori.required' => 'Nama Kategori wajib diisi',
        'nama_kategori.regex' => 'Nama Kategori hanya boleh mengandung huruf dan spasi',
        'nama_kategori.min' => 'Nama Kategori harus memiliki minimal 3 karakter',
        'nama_kategori.max' => 'Nama Kategori tidak boleh lebih dari 50 karakter',

        'gambar_kategori.required' => 'Gambar Kategori wajib diisi',
        'gambar_kategori.image' => 'Gambar Kategori harus berupa gambar',
        'gambar_kategori.mimes' => 'Gambar Kategori hanya boleh memiliki format jpg atau png',
        'gambar_kategori.min' => 'Ukuran Gambar Kategori tidak boleh kurang dari 10 KB',
        'gambar_kategori.max' => 'Ukuran Gambar Kategori tidak boleh lebih dari 2048 KB',
    ]);

    // Mengambil kategori berdasarkan ID
    $kategori = Kategori::find($id);

    // Jika ada gambar kategori yang lama dan gambar baru diupload, hapus gambar lama
    if ($request->hasFile('gambar_kategori') && $kategori->gambar_kategori) {
        $oldImagePath = public_path('img/' . $kategori->gambar_kategori);

        // Hapus gambar lama jika ada
        if (file_exists($oldImagePath)) {
            unlink($oldImagePath);
        }
    }

    // Jika tidak ada gambar yang diupload, gunakan gambar yang lama
    $namaFile = $kategori->gambar_kategori; 

    if ($request->hasFile('gambar_kategori')) {
        $nm = $request->gambar_kategori;
        $namaFile = $nm->getClientOriginalName();

        // Memindahkan file gambar yang baru
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
