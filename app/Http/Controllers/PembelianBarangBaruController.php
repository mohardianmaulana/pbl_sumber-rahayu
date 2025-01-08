<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\HargaBarang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PembelianBarangBaruController extends Controller
{
    public function scanPage()
    {
        return view('scan');
    }

    // Memproses hasil scan
    public function cekQrCode(Request $request)
    {
        // Validasi input
        $request->validate([
            'id_qr' => 'required',
        ]);

        // Cek apakah QR code sudah ada di database
        $exists = Barang::where('id_qr', $request->id_qr)->exists();

        // Jika sudah ada, kirim response bahwa data sudah ada
        if ($exists) {
            return response()->json(['exists' => true]);
        } else {
            // Jika tidak ada, kirim response bahwa data belum ada
            return response()->json(['exists' => false]);
        }
    }

    public function create(Request $request)
    {
        // Mengambil nilai id_qr dari request jika ada
        $id_qr = $request->input('id_qr');

        // Mengambil data supplier dan kategori dari database
        $supplier = Supplier::where('status', 1)->get();
        $kategori = Kategori::where('status', 1)->get();

        // Mengirimkan id_qr ke view jika ada
        return view('pembelian.create_barang', compact('kategori', 'supplier', 'id_qr'));
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|min:3|max:50',
            'jumlah' => 'required|numeric|min:1|max:99999999',
            'harga_beli' => 'required|numeric|min:1|max:999999999999999',
            'harga_jual' => 'required|numeric|min:1|max:999999999999999',
            'minLimit' => 'required|numeric|min:1|max:99999999',
            'maxLimit' => [
                'required',
                'numeric',
                'min:1',
                'max:99999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->minLimit) {
                        $fail('Max Limit tidak boleh lebih kecil daripada Min Limit');
                    }
                }
            ],
            'kategori_id' => 'required|numeric|min:1|max:99999999',
            'supplier_id' => 'required|numeric|min:1|max:99999999',
            'gambar' => 'nullable|image|file|mimes:jpg,png|min:10|max:2048',
        ], [
            'nama.required' => 'Nama Barang harus diisi',
            'nama.regex' => 'Nama Barang tidak boleh menggunakan simbol',
            'nama.min' => 'Nama tidak boleh kurang dari 3 karakter',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 1',
            'jumlah.max' => 'Jumlah tidak boleh lebih dari 99999999',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'harga_beli.required' => 'Harga beli harus diisi',
            'harga_beli.min' => 'Harga beli tidak boleh kurang dari 0',
            'harga_beli.max' => 'Harga beli tidak boleh lebih dari 999999999999999',
            'harga_beli.numeric' => 'Harga beli harus berupa angka',
            'harga_jual.required' => 'Harga jual harus diisi',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0',
            'harga_jual.max' => 'Harga jual tidak boleh lebih dari 999999999999999',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            'minLimit.required' => 'Min Limit harus diisi',
            'minLimit.min' => 'Min Limit tidak boleh kurang dari 0',
            'minLimit.max' => 'Min Limit tidak boleh lebih dari 999999999999999',
            'minLimit.numeric' => 'Min Limit harus berupa angka',
            'maxLimit.required' => 'Max Limit harus diisi',
            'maxLimit.min' => 'Max Limit tidak boleh kurang dari 0',
            'maxLimit.max' => 'Max Limit tidak boleh lebih dari 999999999999999',
            'maxLimit.numeric' => 'Max Limit harus berupa angka',
            'kategori_id.required' => 'Pilih salah satu kategori!',
            'kategori_id.min' => 'Kategori id tidak boleh kurang dari 0',
            'kategori_id.max' => 'Kategori id tidak boleh lebih dari 99999999',
            'kategori_id.numeric' => 'Kategori harus berupa angka',
            'supplier_id.required' => 'Pilih salah satu supplier!',
            'supplier_id.min' => 'Supplier id tidak boleh kurang dari 0',
            'supplier_id.max' => 'Supplier id tidak boleh lebih dari 99999999',
            'supplier_id.numeric' => 'Supplier harus berupa angka',
            'gambar.min' => 'Ukuran Gambar tidak boleh kurang dari 10 kb',
            'gambar.max' => 'Ukuran Gambar tidak boleh lebih dari 2048 kb',
            'gambar.mimes' => 'Gambar harus berupa file dengan format jpg atau png',
        ]);        

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $namaFile = null;
        if ($request->hasFile('gambar')) {
            $nm = $request->gambar;
            $namaFile = $nm->getClientOriginalName();
            // $namaFile = time().rand(100,999).".".$nm->getClientOriginalExtension();

            $nm->move(public_path() . '/img', $namaFile);
        }

        // $barang->save();

        // Simpan ke tabel barang
        $barang = Barang::create([
            'id_qr' => $request->id_qr,
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,  // Jangan menambahkan jumlah di sini, biarkan sebagai jumlah input
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'minLimit' => $request->minLimit,
            'maxLimit' => $request->maxLimit,
            'kategori_id' => $request->kategori_id,
            'status' => 1,
            'gambar' => $namaFile,
        ]);

        // Hitung total harga
        $harga_beli = $request->harga_beli;
        $jumlah = $request->jumlah;
        $totalHarga = $harga_beli * $jumlah;

        // Simpan ke tabel pembelian
        $pembelian = Pembelian::create([
            'supplier_id' => $request->supplier_id,
            'total_item' => $jumlah,
            'total_harga' => $totalHarga,
            'status' => 1,
            'tanggal_transaksi' => now(),
            'user_id' => Auth::id(),
        ]);

        // Simpan ke tabel pivot barang_pembelian
        $pembelian->barangs()->attach($barang->id, [
            'jumlah' => $jumlah,
            'harga' => $harga_beli,
            'jumlah_itemporary' => $jumlah,
            'status' => 1,
        ]);

        // Perbarui stok barang
        $barang->jumlah = $jumlah;  // Menggunakan jumlah input asli
        $barang->save();

                // Buat baris baru dengan harga dan supplier baru
                HargaBarang::create([
                    'barang_id' => $barang->id,
                    'harga_beli' => $harga_beli,
                    'harga_jual' => $request->harga_jual,
                    'supplier_id' => $request->supplier_id,
                    'tanggal_mulai' => now(),
                    'tanggal_selesai' => null,
                ]);

        return redirect()->to('pembelian')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id)
    {
        // Ambil data pembelian berdasarkan ID
        $pembelian = Pembelian::with('barangs')->findOrFail($id);
        // Ambil ID barang dari pembelian, misalnya dengan mengambil ID barang yang terkait
        $barang = $pembelian->barangs()->first(); // Ambil barang pertama yang terkait dengan pembelian ini
        $supplier = Supplier::all(); // Ambil data supplier
        $kategori = Kategori::all(); // Ambil data kategori

        // Mengirimkan data pembelian ke view
        return view('pembelian.editBarangBaru', compact('pembelian', 'supplier', 'kategori', 'barang'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|regex:/^[a-zA-Z0-9\s]+$/|min:3|max:50',
            'jumlah' => 'required|numeric|min:1|max:99999999',
            'harga_beli' => 'required|numeric|min:1|max:999999999999999',
            'harga_jual' => 'required|numeric|min:1|max:999999999999999',
            'minLimit' => 'required|numeric|min:1|max:99999999',
            'maxLimit' => [
                'required',
                'numeric',
                'min:1',
                'max:99999999',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value < $request->minLimit) {
                        $fail('Max Limit tidak boleh lebih kecil daripada Min Limit');
                    }
                }
            ],
            'kategori_id' => 'required|numeric|min:1|max:99999999',
            'supplier_id' => 'required|numeric|min:1|max:99999999',
            'gambar' => 'nullable|image|file|mimes:jpg,png|min:10|max:2048',
        ], [
            'nama.required' => 'Nama Barang harus diisi',
            'nama.regex' => 'Nama Barang tidak boleh menggunakan simbol',
            'nama.min' => 'Nama tidak boleh kurang dari 3 karakter',
            'nama.max' => 'Nama tidak boleh lebih dari 50 karakter',
            'jumlah.required' => 'Jumlah harus diisi',
            'jumlah.min' => 'Jumlah tidak boleh kurang dari 1',
            'jumlah.max' => 'Jumlah tidak boleh lebih dari 99999999',
            'jumlah.numeric' => 'Jumlah harus berupa angka',
            'harga_beli.required' => 'Harga beli harus diisi',
            'harga_beli.min' => 'Harga beli tidak boleh kurang dari 0',
            'harga_beli.max' => 'Harga beli tidak boleh lebih dari 999999999999999',
            'harga_beli.numeric' => 'Harga beli harus berupa angka',
            'harga_jual.required' => 'Harga jual harus diisi',
            'harga_jual.min' => 'Harga jual tidak boleh kurang dari 0',
            'harga_jual.max' => 'Harga jual tidak boleh lebih dari 999999999999999',
            'harga_jual.numeric' => 'Harga jual harus berupa angka',
            'minLimit.required' => 'Min Limit harus diisi',
            'minLimit.min' => 'Min Limit tidak boleh kurang dari 0',
            'minLimit.max' => 'Min Limit tidak boleh lebih dari 999999999999999',
            'minLimit.numeric' => 'Min Limit harus berupa angka',
            'maxLimit.required' => 'Max Limit harus diisi',
            'maxLimit.min' => 'Max Limit tidak boleh kurang dari 0',
            'maxLimit.max' => 'Max Limit tidak boleh lebih dari 999999999999999',
            'maxLimit.numeric' => 'Max Limit harus berupa angka',
            'kategori_id.required' => 'Pilih salah satu kategori!',
            'kategori_id.min' => 'Kategori id tidak boleh kurang dari 0',
            'kategori_id.max' => 'Kategori id tidak boleh lebih dari 99999999',
            'kategori_id.numeric' => 'Kategori harus berupa angka',
            'supplier_id.required' => 'Pilih salah satu supplier!',
            'supplier_id.min' => 'Supplier id tidak boleh kurang dari 0',
            'supplier_id.max' => 'Supplier id tidak boleh lebih dari 99999999',
            'supplier_id.numeric' => 'Supplier harus berupa angka',
            'gambar.min' => 'Ukuran Gambar tidak boleh kurang dari 10 kb',
            'gambar.max' => 'Ukuran Gambar tidak boleh lebih dari 2048 kb',
            'gambar.mimes' => 'Gambar harus berupa file dengan format jpg atau png',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Ambil data pembelian berdasarkan ID
        $pembelian = Pembelian::with('barangs')->findOrFail($id);
        $barang = $pembelian->barangs()->first();

        $namaFile = $barang->gambar;
        if ($request->hasFile('gambar')) {
            $nm = $request->gambar;
            $namaFile = $nm->getClientOriginalName();

            $nm->move(public_path() . '/img', $namaFile);
        }

        // Update data barang
        $barang->update([
            'id_qr' => $request->id_qr,
            'nama' => $request->nama,
            'jumlah' => $request->jumlah,
            'harga_jual' => $request->harga_jual,
            'harga_beli' => $request->harga_beli,
            'minLimit' => $request->minLimit,
            'maxLimit' => $request->maxLimit,
            'kategori_id' => $request->kategori_id,
            'gambar' => $namaFile,
        ]);

        $jumlah = $request->jumlah;
        $harga_beli = $request->harga_beli;
        $totalHarga = $harga_beli * $jumlah;

        // Update data pembelian
        $pembelian = Pembelian::whereHas('barangs', function ($query) use ($barang) {
            $query->where('barang_id', $barang->id);
        })->firstOrFail();

        $pembelian->update([
            'supplier_id' => $request->supplier_id,
            'barang_id' => $barang->id,
            'total_item' => $jumlah,
            'total_harga' => $totalHarga,
            'tanggal_transaksi' => now(),
            'user_id' => Auth::id(),
        ]);

        // Update data pivot barang_pembelian
        $pivotData = $pembelian->barangs()->where('barang_id', $barang->id)->first();
        if ($pivotData) {
            $jumlah_itemporary = $pivotData->pivot->jumlah_itemporary;
            $jumlah = $request->jumlah;

            // dd($jumlah_itemporary);
            // dd($jumlah);

            // Logika untuk menyesuaikan jumlah barang
            if ($jumlah < $jumlah_itemporary) {
                $selisihJumlah = $jumlah_itemporary - $jumlah;
                $barang->jumlah -= $selisihJumlah;
                $barang->save();
            } else if ($jumlah > $jumlah_itemporary) {
                $selisihJumlah = $jumlah - $jumlah_itemporary;
                $barang->jumlah += $selisihJumlah;
                $barang->save();
            }
            $pembelian->barangs()->updateExistingPivot($barang->id, [
                'jumlah' => $jumlah,
                'harga' => $harga_beli,
                'jumlah_itemporary' => $jumlah,
                'status' => 1,
            ]);
        } else {
            // Jika data barang belum ada di pivot table, tambahkan data baru
            $pembelian->barangs()->attach($barang->id, [
                'jumlah' => $jumlah,
                'harga' => $harga_beli,
                'jumlah_itemporary' => $jumlah,
            ]);
        }

        // Tambahkan logika ini setelah validasi dan sebelum pengembalian respons sukses
        $hargaBarangTerakhir = HargaBarang::where('barang_id', $barang->id)
        ->orderBy('tanggal_mulai', 'desc')
        ->first();

        if (!$hargaBarangTerakhir || $hargaBarangTerakhir->harga_beli != $request->harga_beli || $hargaBarangTerakhir->harga_jual != $request->harga_jual) {
            if ($hargaBarangTerakhir) {
                $hargaBarangTerakhir->update(['tanggal_selesai' => now()]);
            }

        HargaBarang::create([
            'barang_id' => $barang->id,
            'harga_beli' => $request->harga_beli,
            'harga_jual' => $request->harga_jual,
            'supplier_id' => $request->supplier_id,
            'tanggal_mulai' => now(),
            'tanggal_selesai' => null,
        ]);
        }


        return redirect()->to('pembelian')->with('success', 'Produk berhasil diperbarui');
    }
}
