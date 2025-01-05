<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\Supplier;
use App\Models\Barang;
use App\Models\Kategori;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $pembelian = Pembelian::tampil();

        // Ambil data supplier dari database dengan kondisi status 1
        $supplier = Supplier::where('status', 1)->get();
        $user = User::all();

        return view('pembelian.index', compact('pembelian', 'supplier', 'user'));
    }

    public function oldPurchases(Request $request)
    {
        $pembelian = Pembelian::tampilLama();

        // Ambil semua data supplier dan user untuk halaman pembelian lama
        $supplier = Supplier::all();
        $user = User::all();

        return view('pembelian.indexLama', compact('pembelian', 'supplier', 'user'));
    }

    public function create(Request $request)
    {
        $dataBarang = Session()->get('pembelian_barang', []); // Ambil data barang dari sesi

         // Log data sesi
        Log::info('Isi sesi pembelian_barang:', $dataBarang);

        $supplier_id = $request->query('supplier_id');
        
        // Ambil semua data yang diperlukan untuk form pembelian dari model Pembelian
        $data = Pembelian::buat($supplier_id);

        // Pastikan customer ada
        $supplier = $data['supplier'];

        $barang = $data['barang'];

        // Kirim data ke view untuk ditampilkan
        return view(
            'pembelian.create',
            [
                'data' => $data, // Data dari method buat
                'dataBarang' => $dataBarang,
                'supplier' => $supplier,
                'barang' => $barang,
            ]
        );
    }

    public function cekQR(Request $request)
    {
        $barang = Barang::where('id_qr', $request->id_qr)->first();

        if ($barang) {
            return response()->json([
                'exists' => true,
                'id' => $barang->id,
                'nama' => $barang->nama,
                'harga' => $barang->harga_beli,
                // // Sertakan customer_id jika perlu      
            ]);
        } else {
            return response()->json(['exists' => false]);
            // return view('penjualan.create')->with('error', 'barang tidak ada');

        }
    }

    public function tambahSesi(Request $request)
{
    // Ambil barang dari sesi
    $barangPembelian = Session::get('pembelian_barang', []);

    // Jika barang sudah ada di sesi
    if (isset($barangPembelian[$request->id])) {
        // Cek sumber permintaan (modal atau scan QR)
        if ($request->input('source') === 'modal') {
            // Jika berasal dari modal, kembalikan pesan error
            return response()->json(['status' => 'error', 'message' => 'Barang sudah dipilih.'], 400);
        }

        // Tambahkan atau perbarui barang di sesi
        if (!isset($barangPembelian[$request->id])) {
            $barangPembelian[$request->id] = [
                'nama' => $request->nama,
                'harga' => $request->harga,
                'jumlah' => $request->jumlah, // Set nilai jumlah langsung dari request
            ];
        } else {
            $barangPembelian[$request->id]['jumlah'] += $request->jumlah; // Tambahkan jumlah
        }
        // // Jika berasal dari scan QR, tambahkan jumlah barang
        // if (!isset($barangPembelian[$request->id]['jumlah'])) {
        //     $barangPembelian[$request->id]['jumlah'] = 1; // Default jumlah jika belum ada
        // }
        // $barangPembelian[$request->id]['jumlah'] += 1; // Tambahkan jumlah

        // Simpan kembali barang ke sesi
        Session::put('pembelian_barang', $barangPembelian);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah barang berhasil ditambahkan!',
            'data' => $barangPembelian,
        ]);
    }

    // Jika barang belum ada di sesi, tambahkan sebagai barang baru
    $barangPembelian[$request->id] = [
        'id' => $request->id,
        'nama' => $request->nama,
        'harga' => $request->harga,
        'jumlah' => 1, // Inisialisasi jumlah
    ];

    // Simpan kembali barang ke sesi
    Session::put('pembelian_barang', $barangPembelian);

    return response()->json([
        'status' => 'success',
        'message' => 'Barang berhasil ditambahkan ke sesi!',
        'data' => $barangPembelian,
    ]);
}



    public function hapusSesi(Request $request)
    {
        $data = Session::get('pembelian_barang', []);
        unset($data[$request->id]);
        Session::put('pembelian_barang', $data);

        return response()->json(['message' => 'Barang berhasil dihapus dari sesi']);
    }

    public function store(Request $request)
    {
        // Validasi data request
        $request->validate([
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barang,id',
            'harga_beli.*' => 'required|numeric|min:1',
            'jumlah.*' => 'required|numeric|min:1',
        ], [
            'barang_id.required' => 'Harus memilih setidaknya satu barang',
            'barang_id.*.required' => 'Barang tidak valid',
            'harga_beli.*.required' => 'Harga beli wajib diisi',
            'harga_beli.*.numeric' => 'Harga beli harus berupa angka',
            'harga_beli.*.min' => 'Harga beli tidak boleh kurang dari 1',
            'jumlah.*.required' => 'Jumlah wajib diisi',
            'jumlah.*.numeric' => 'Jumlah harus berupa angka',
            'jumlah.*.min' => 'Jumlah tidak boleh kurang dari 1',
        ]);

        // Panggil method storePembelian dari model untuk menangani proses penyimpanan
        Pembelian::tambahPembelian($request->all());

        return redirect()->to('pembelian')->with('success', 'Pembelian berhasil disimpan.');
    }

    public function editTambahSesi(Request $request)
{
    // Ambil barang dari sesi
    $barangEditPembelian = Session::get('edit_pembelian_barang', []);

    // Jika barang sudah ada di sesi
    if (isset($barangEditPembelian[$request->id])) {
        // Cek sumber permintaan (modal atau scan QR)
        if ($request->input('source') === 'modal') {
            return response()->json(['status' => 'error', 'message' => 'Barang sudah dipilih.'], 400);
        }

        // Perbarui jumlah barang
        $barangEditPembelian[$request->id]['jumlah'] += $request->jumlah;

        // Simpan kembali barang ke sesi
        Session::put('edit_pembelian_barang', $barangEditPembelian);

        return response()->json([
            'status' => 'success',
            'message' => 'Jumlah barang berhasil ditambahkan!',
            'data' => $barangEditPembelian,
        ]);
    }

    // Jika barang belum ada di sesi, tambahkan sebagai barang baru
    $barangEditPembelian[$request->id] = [
        'id' => $request->id,
        'nama' => $request->nama,
        'harga' => $request->harga,
        'jumlah' => $request->jumlah, // Inisialisasi jumlah dari request
    ];

    // Simpan kembali barang ke sesi
    Session::put('edit_pembelian_barang', $barangEditPembelian);

    return response()->json([
        'status' => 'success',
        'message' => 'Barang berhasil ditambahkan ke sesi!',
        'data' => $barangEditPembelian,
    ]);
}


    public function editHapusSesi(Request $request)
    {

        $data = Session::get('edit_pembelian_barang', []);
        unset($data[$request->id]); // Hapus barang berdasarkan ID
        Session::put('edit_pembelian_barang', $data); // Update sesi

        return response()->json(['message' => 'Barang berhasil dihapus dari sesi']);
    }

    public function hapusSemuaSesi()
    {
        // Hapus semua data sesi yang terkait
        session()->forget('pembelian_barang'); // Ganti dengan nama sesi yang Anda gunakan
        session()->forget('edit_pembelian_barang'); // Ganti dengan nama sesi yang Anda gunakan
        return redirect()->route('pembelian');
    }

    public function edit($id)
    {
        // $dataBarang = Session()->get('edit_pembelian_barang', []); // Ambil data barang dari sesi

        // Panggil method model untuk mengambil data yang diperlukan untuk mengedit pembelian
        $data = Pembelian::ganti($id);

        // Cek apakah data berisi pesan error (misalnya jika pembelian terlalu lama untuk diedit)
        if (isset($data['error'])) {
            return redirect()->route('pembelian.lama')->with('error', $data['error']);
        }

        // Jika data valid, kirim data ke view
        return view('pembelian.edit', $data);
    }


    public function update(Request $request, $id)
    {
        // Panggil method model untuk memperbarui pembelian
        $result = Pembelian::gantiPembelian($request->all(), $id);

        // Cek apakah ada error dalam hasil update
        if (isset($result['error'])) {
            return redirect()->route('pembelian.lama')->with('error', $result['error']);
        }

        // Jika berhasil, alihkan dengan pesan sukses
        return redirect()->to('pembelian')->with('success', 'Pembelian berhasil diperbarui.');
    }

    public function laporan()
{
    // Ambil laporan pembelian dengan join ke beberapa tabel
    $pembelian = DB::table('pembelian')
        ->join('barang_pembelian', 'pembelian.id', '=', 'barang_pembelian.pembelian_id')
        ->join('barang', 'barang_pembelian.barang_id', '=', 'barang.id')
        ->join('supplier', 'pembelian.supplier_id', '=', 'supplier.id')
        ->join('user', 'pembelian.user_id', '=', 'user.id')
        ->select(
            'pembelian.id', // Ambil ID pembelian untuk mengelompokkan data
            'pembelian.tanggal_transaksi',
            'supplier.nama as nama_supplier',
            DB::raw('GROUP_CONCAT(barang.nama SEPARATOR ", ") as barang_nama'), // Gabungkan nama barang
            DB::raw('SUM(pembelian.total_harga) as total_harga'), // Hitung total harga pembelian
            DB::raw('SUM(barang_pembelian.jumlah) as total_item'), // Hitung total jumlah item
            'user.name as nama_user',
        )
        ->groupBy('pembelian.id', 'pembelian.tanggal_transaksi', 'supplier.nama', 'user.name') // Kelompokkan berdasarkan id pembelian
        ->get();

    // Ambil data penjualan hari ini
    $pembelianDetail = Pembelian::join('user', 'pembelian.user_id', '=', 'user.id')
    ->leftJoin('supplier', 'pembelian.supplier_id', '=', 'supplier.id') // Join dengan tabel supplier
    ->select('pembelian.*', 'user.name as user_nama', 'supplier.nama as supplier_nama') // Pilih nama customer
    ->orderBy('pembelian.tanggal_transaksi', 'desc')
    ->get();

    // Kirim data laporan pembelian ke view
    return view('pembelian.laporanpembelian', compact('pembelian', 'pembelianDetail'));
}

}
