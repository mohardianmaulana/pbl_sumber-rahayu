<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\HargaBarang;
use App\Models\Pembelian;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use App\Models\Persetujuan;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BarangController extends Controller
{
    public function katalog(Request $request)
    {
        // Menambahkan pengambilan data kategori
        $kategori = Kategori::all();

        // Jika ada kategori yang dipilih
        if ($request->has('kategori_id') && $request->kategori_id != '') {
            $barang = Barang::join('kategori', 'barang.kategori_id', '=', 'kategori.id')
                        ->join('harga_barang', 'barang.id', '=', 'harga_barang.barang_id')
                        ->where('barang.kategori_id', $request->kategori_id)
                        ->select(
                            'barang.id',
                            'barang.nama',
                            'barang.kategori_id',
                            'kategori.nama_kategori as kategori_nama',
                            'kategori.gambar_kategori as kategori_gambar',  // Menambahkan gambar kategori
                            'barang.gambar',  // Menambahkan gambar barang
                            'barang.jumlah',
                            'barang.minLimit',
                            'barang.maxLimit',
                            'harga_barang.harga_jual'  // Pastikan harga ikut disertakan jika dibutuhkan
                        )
                        ->with('kategori')  // Mengambil relasi kategori jika dibutuhkan
                        ->get();
        } else {
            // Jika tidak ada kategori yang dipilih, tampilkan semua barang
            $barang = Barang::getAllBarangWithKategoriAndHarga();
        }

        return view('katalog', compact('barang', 'kategori'));
    }

    public function index(Request $request)
    {
        // Memanggil method di model Barang untuk mendapatkan data barang
        $barang = Barang::getAllBarangWithKategoriAndHarga();

        // Memanggil method untuk mendapatkan rata-rata harga beli
        $rataRataHargaBeli = Barang::getAverageHargaBeli();

        // Mengambil semua data kategori dari tabel kategori
        $kategori = Kategori::all();

        // Mengembalikan view dengan data yang dibutuhkan
        return view('barang.index', compact('barang', 'kategori', 'rataRataHargaBeli'));
    }


    public function arsip(Request $request)
    {
        // Memanggil method di model Barang untuk mendapatkan data barang
        $barang = Barang::arsip();

        // Memanggil method untuk mendapatkan rata-rata harga beli
        $rataRataHargaBeli = Barang::getAverageHargaBeli();

        // Mengambil semua data kategori dari tabel 'kategori'
        $kategori = Kategori::all();
        return view('barang.indexArsip', compact('barang', 'rataRataHargaBeli', 'kategori'));
    }

    public function pulihkan($id)
    {
        $barang = Barang::pulihkan($id);

        return redirect()->route('barang.lama')->with('success', 'Barang berhasil dipulihkan.');
    }

    public function arsipkan($id)
    {
        $barang = Barang::arsipkan($id);

        return redirect()->route('admin')->with('success', 'Barang berhasil diarsipkan.');
    }

    // public function scanPage()
    // {
    //     return view('scan');
    // }

    // // Memproses hasil scan
    // public function cekQrCode(Request $request)
    // {
    //     // Validasi input
    //     $request->validate([
    //         'id_qr' => 'required',
    //     ]);

    //     // Cek apakah QR code sudah ada di database
    //     $barang = Barang::where('id_qr', $request->id_qr)->first();

    //     // Jika barang ada, kirim nama barang dan informasi bahwa QR sudah ada
    //     if ($barang) {
    //         return response()->json(['exists' => true, 'nama' => $barang->nama]);
    //     } else {
    //         // Jika tidak ada, kirim response bahwa data belum ada
    //         return response()->json(['exists' => false]);
    //     }
    // }


    // public function create(Request $request)
    // {
    //     // Mengambil nilai id_qr dari request jika ada
    //     $id_qr = $request->input('id_qr');

    //     // Memanggil method dari model Pembelian untuk mendapatkan data
    //     $formData = Pembelian::tambahBaru();

    //     // Mengirimkan data ke view termasuk id_qr jika ada
    //     return view('pembelian.create_barang', array_merge($formData, compact('id_qr')));
    // }


    // public function store(Request $request)
    // {
    //     // Memanggil method di model Barang untuk melakukan penyimpanan
    //     $result = Barang::storeBarang($request);

    //     // Memeriksa status hasil dari model
    //     if ($result['status'] == 'error') {
    //         // Jika ada error validasi, kembali ke form dengan error
    //         return redirect()->back()
    //             ->withErrors($result['errors'])
    //             ->withInput();
    //     }

    //     // Jika berhasil, redirect ke halaman pembelian dengan pesan sukses
    //     return redirect()->to('pembelian')->with('success', $result['message']);
    // }


    //     public function create()
    //     {
    //         $kategori = Kategori::all();
    //         return view('barang.create',compact('kategori'));
    //     }

    //     public function store(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'nama' => 'required',
    //         'jumlah' => 'required|numeric|min:0',
    //         'harga_jual' => 'required|numeric|min:1',
    //         'minLimit' => 'required|numeric',
    //         'maxLimit' => [
    //             'required',
    //             'numeric',
    //             function ($attribute, $value, $fail) use ($request) {
    //                 if ($value < $request->minLimit) {
    //                     $fail('Max Limit tidak boleh lebih kecil daripada Min Limit');
    //                 }
    //             }
    //         ],
    //         'kategori_id' => 'required',
    //     ], [
    //         'nama.required' => 'Nama Barang wajib diisi',
    //         'jumlah.required' => 'Jumlah wajib diisi',
    //         'jumlah.min'=>'Jumlah tidak boleh kurang dari 0',
    //         'harga_jual'=> 'Harga jual wajib diisi',
    //         'minLimit.required' => 'Min Limit wajib diisi',
    //         'maxLimit.required' => 'Max Limit wajib diisi',
    //         'kategori_id.required' => 'Kategori wajib diisi',
    //     ]);

    //     if ($validator->fails()) {
    //         return redirect()->back()
    //                          ->withErrors($validator)
    //                          ->withInput();
    //     }

    //     $barang = [
    //         'nama' => $request->nama,
    //         'jumlah' => $request->jumlah,
    //         'harga_jual' => $request->harga_jual,
    //         'minLimit' => $request->minLimit,
    //         'maxLimit' => $request->maxLimit,
    //         'kategori_id' => $request->kategori_id,
    //     ];

    //     Barang::create($barang);

    //     return redirect()->to('barang')->with('success', 'Produk berhasil ditambahkan');
    // }

    public function checkEdit($id)
    {
        $userId = Auth::id();

        // Memanggil method di model Persetujuan
        $result = Persetujuan::checkEditBarang($id, $userId);

        // Memproses hasil dari model
        if (isset($result['redirect'])) {
            return redirect()->to($result['redirect'])->with($result['status'] ?? 'info', $result['message'] ?? '');
        } elseif (isset($result['view'])) {
            return view($result['view'], $result['data']);
        }
    }



    public function edit($id)
    {
        // Memanggil method di model Barang untuk mendapatkan data
        $barang = Barang::ubah($id);

        // Mengambil semua kategori
        $kategori = Kategori::all();

        // Mengirimkan data ke view
        return view('barang.edit', compact('barang', 'kategori'));
    }


    public function update(Request $request, $id)
    {
        // Memanggil method di model Barang untuk melakukan update
        $result = Barang::updateBarang($request, $id);

        // Memeriksa status hasil dari model
        if ($result['status'] == 'error') {
            // Jika ada error validasi, kembali ke form dengan error
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        // Jika update berhasil, redirect ke halaman barang dengan pesan sukses
        return redirect()->to('barang')->with('success', $result['message']);
    }

    //     public function destroy($id)
    //     {
    //         Barang::where('id', $id)->delete();
    //         return redirect()->to('barang')->with('success', 'Berhasil menghapus data produk');
    //     }

    //     public function barang(Request $request)
    // {
    //     $jumlahbaris = 10;
    //     $kategori = Kategori::all();

    //     // Buat query dasar dengan join.
    //     $query = Toko::join('kategori', 'toko.kategori_id', '=', 'kategori.id')
    //                  ->select('toko.*', 'kategori.nama_kategori as kategori_nama');

    //     // Filter berdasarkan kategori jika diberikan
    //     if ($request->has('kategori_id') && $request->kategori_id != '') {
    //         $query->where('toko.kategori_id', $request->kategori_id);
    //     }

    //     // Jika kata kunci disediakan, tambahkan sebagai filter.
    //     if ($request->has('katakunci') && strlen($request->katakunci)) {
    //         $query->where('toko.nama', 'like', "%{$request->katakunci}%");
    //     }

    //     // Urutkan dan paginasi hasil query.
    //     $data = $query->orderBy('toko.kode', 'asc')->paginate($jumlahbaris);

    //     return view('barang.barang', ['data' => $data, 'kategori' => $kategori]);
    // }

    // public function exportExcel()
    // {
    //     $tanggalDatabase = Penanggalan::orderBy('tanggal', 'asc')->first();

    //     $tanggalDatabase = $tanggalDatabase->tanggal;
    //     $tanggalSekarang = Carbon::now()->format('Y-m-d');
    //     if ($tanggalSekarang != $tanggalDatabase) {
    //         $fileName = 'toko' . Carbon::now()->format('Y_m_d_His') . '.xlsx';

    //         // Ekspor dan simpan file ke folder spesifik
    //         Excel::store(new TokoExport, $fileName, 'local');

    //         // Salin file ke folder tujuan
    //         copy(storage_path('app/' . $fileName), 'C:/Users/user/Backup_Rahayu/' . $fileName);
    //         // 1. Hapus semua data dari tabel 'tanggalan'
    //         DB::table('penanggalan')->delete();

    //         // 2. Tambahkan tanggal saat ini ke dalam tabel 'tanggalan'
    //         DB::table('penanggalan')->insert([
    //             'tanggal' => $tanggalSekarang,
    //             'created_at' => now(),
    //             'updated_at' => now(),
    //         ]);

    //     }
    //     return redirect('dashboard');
    // }
    public function generateCode($id)
    {
        // Generate 6 digit random code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update the 'kodePersetujuan' field in 'persetujuan' table where 'id' equals $id
        $persetujuan = Persetujuan::find($id);
        if ($persetujuan) {
            // Temporarily disable timestamps
            $persetujuan->timestamps = false;

            // Update the kodePersetujuan field
            $persetujuan->kodePersetujuan = $code;

            // Save the changes without updating timestamps
            $persetujuan->save();

            return response()->json(['success' => true, 'kodePersetujuan' => $code]);
        } else {
            return response()->json(['success' => false, 'message' => 'Data persetujuan tidak ditemukan.'], 404);
        }
    }
}
