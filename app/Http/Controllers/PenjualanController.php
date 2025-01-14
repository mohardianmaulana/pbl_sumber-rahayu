<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Customer;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class PenjualanController extends Controller
{
    public function index()
    {
        // Memanggil method dari model Penjualan
        $data = Penjualan::tampil();

        // Mengirim data penjualan dan customer ke view
        return view('penjualan.index', [
            'penjualan' => $data['penjualan'],
            'customer' => $data['customers'],
        ]);
    }


    public function oldPurchases(Request $request)
    {
        $penjualan = Penjualan::tampilLama();

        return view('penjualan.indexLama', compact('penjualan'));
    }

    public function scanPage()
    {
        return view('penjualan.scan_qr');
    }

    public function cekQR(Request $request)
    {
        // $barang1 = array();
        $barang = Barang::where('id_qr', $request->id_qr)->first();
        // dd($barang1);

        if ($barang) {
            // array_push($barang1, $barang);
            // return view('penjualan.create', compact('barang1'));
            return response()->json([
                'exists' => true,
                'id' => $barang->id,
                'nama' => $barang->nama,
                'harga' => $barang->harga_jual,
                // // Sertakan customer_id jika perlu      
            ]);
        } else {
            return response()->json(['exists' => false]);
            // return view('penjualan.create')->with('error', 'barang tidak ada');

        }
    }



    public function create(Request $request)
    {
        $dataBarang = Session()->get('penjualan_barang', []); // Ambil data barang dari sesi

        // Ambil customer_id dari query parameter
        $customer_id = $request->query('customer_id');

        // Panggil method `tambah` dari model Penjualan
        $data = Penjualan::tambah($customer_id);

        // Pastikan customer ada
        $customer = $data['customer'];

        $barang = $data['barang'];

        // Mengirim data ke view
        return view(
            'penjualan.create',
            [
                'data' => $data, // Data dari method tambah
                'dataBarang' => $dataBarang,
                'customer' => $customer,
                'barang' => $barang,
            ]
        );
    }

    public function tambahSesi(Request $request)
    {
        // Ambil barang dari sesi
        $barangPenjualan = Session::get('penjualan_barang', []);

        // Jika barang sudah ada di sesi
        if (isset($barangPenjualan[$request->id])) {
            // Cek sumber permintaan (modal atau scan QR)
            if ($request->input('source') === 'modal') {
                // Jika berasal dari modal, kembalikan pesan error
                return response()->json(['status' => 'error', 'message' => 'Barang sudah dipilih.'], 400);
            }

            // Tambahkan atau perbarui barang di sesi
            if (!isset($barangPenjualan[$request->id])) {
                $barangPenjualan[$request->id] = [
                    'nama' => $request->nama,
                    'harga' => $request->harga,
                    'jumlah' => $request->jumlah, // Set nilai jumlah langsung dari request
                ];
            } else {
                $barangPenjualan[$request->id]['jumlah'] += $request->jumlah; // Tambahkan jumlah
            }
            // // Jika berasal dari scan QR, tambahkan jumlah barang
            // if (!isset($barangPenjualan[$request->id]['jumlah'])) {
            //     $barangPenjualan[$request->id]['jumlah'] = 1; // Default jumlah jika belum ada
            // }
            // $barangPenjualan[$request->id]['jumlah'] += 1; // Tambahkan jumlah

            // Simpan kembali barang ke sesi
            Session::put('penjualan_barang', $barangPenjualan);

            return response()->json([
                'status' => 'success',
                'message' => 'Jumlah barang berhasil ditambahkan!',
                'data' => $barangPenjualan,
            ]);
        }

        // Jika barang belum ada di sesi, tambahkan sebagai barang baru
        $barangPenjualan[$request->id] = [
            'id' => $request->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
            'jumlah' => 1, // Inisialisasi jumlah
        ];

        // Simpan kembali barang ke sesi
        Session::put('penjualan_barang', $barangPenjualan);

        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil ditambahkan ke sesi!',
            'data' => $barangPenjualan,
        ]);
    }

    // public function tambahSesi(Request $request)
    // {
    //     // Ambil data barang dari request
    //     $barangSesi = [
    //         'id' => $request->id,
    //         'nama' => $request->nama,
    //         'harga' => $request->harga,
    //     ];

    //     // Ambil barang yang sudah ada di sesi
    //     $data = Session::get('penjualan_barang', []);

    //     // Cek apakah barang sudah ada di sesi berdasarkan id
    //     if (isset($data[$request->id])) {
    //         // Jika sudah ada, kembalikan respons dengan status error
    //         return response()->json(['status' => 'error', 'message' => 'Barang sudah dipilih.'], 400);
    //     }

    //     // Jika belum ada, tambahkan barang baru ke sesi
    //     $data[$request->id] = $barangSesi;

    //     // Simpan kembali barang ke sesi
    //     Session::put('penjualan_barang', $data);

    //     // // Simpan data ke sesi
    //     // $data = Session::get('penjualan_barang', []); // Ambil data sesi jika ada
    //     // $data[$request->id] = $barang; // Tambahkan atau update data barang berdasarkan ID
    //     // Session::put('penjualan_barang', $data); // Simpan kembali ke sesi

    //     return response()->json(['message' => 'Barang berhasil ditambahkan ke sesi', 'data' => $data]);
    // }

    public function hapusSesi(Request $request)
    {
        $data = Session::get('penjualan_barang', []);
        unset($data[$request->id]); // Hapus barang berdasarkan ID
        Session::put('penjualan_barang', $data); // Update sesi

        return response()->json(['message' => 'Barang berhasil dihapus dari sesi']);
    }

    public function store(Request $request)
    {
        // Panggil method `storePenjualan` dari model
        $result = Penjualan::tambahPenjualan($request);

        // Jika terjadi error validasi, kembalikan dengan error
        if ($result['status'] == 'error') {
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        // Hapus data sesi setelah pembelian berhasil disimpan
        Session::forget('penjualan_barang'); // Menghapus semua data 'barang' di sesi

        // Jika berhasil, kembalikan dengan pesan sukses
        return redirect()->to('penjualan')->with('success', 'Penjualan berhasil disimpan.');
    }

    public function edit($id)
    {
        // Panggil method `getPenjualanForEdit` dari model
        $data = Penjualan::edit($id);

        // Jika terjadi error (penjualan tidak ditemukan atau lebih dari 1 bulan)
        if ($data['status'] == 'error') {
            $route = isset($data['redirect_route']) ? $data['redirect_route'] : 'penjualan.index';
            return redirect()->route($route)->with('error', $data['message']);
        }

        // Kirimkan data ke view
        return view('penjualan.edit', $data);
    }


    public function editTambahSesi(Request $request)
    {
        // Ambil barang dari sesi
        $barangEditPenjualan = Session::get('edit_penjualan_barang', []);

        // Jika barang sudah ada di sesi
        if (isset($barangEditPenjualan[$request->id])) {
            // Cek sumber permintaan (modal atau scan QR)
            if ($request->input('source') === 'modal') {
                return response()->json(['status' => 'error', 'message' => 'Barang sudah dipilih.'], 400);
            }

            // Perbarui jumlah barang
            $barangEditPenjualan[$request->id]['jumlah'] += $request->jumlah;

            // Simpan kembali barang ke sesi
            Session::put('edit_penjualan_barang', $barangEditPenjualan);

            return response()->json([
                'status' => 'success',
                'message' => 'Jumlah barang berhasil ditambahkan!',
                'data' => $barangEditPenjualan,
            ]);
        }

        // Jika barang belum ada di sesi, tambahkan sebagai barang baru
        $barangEditPenjualan[$request->id] = [
            'id' => $request->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
            'jumlah' => $request->jumlah, // Inisialisasi jumlah dari request
        ];

        // Simpan kembali barang ke sesi
        Session::put('edit_penjualan_barang', $barangEditPenjualan);

        return response()->json([
            'status' => 'success',
            'message' => 'Barang berhasil ditambahkan ke sesi!',
            'data' => $barangEditPenjualan,
        ]);
    }

    public function editHapusSesi(Request $request)
    {
        try {
            // Validasi ID barang
            $request->validate([
                'id' => 'required|numeric',
            ]);

            $barangId = $request->id;

            // Periksa apakah barang ada di tabel pivot
            $existsInDatabase = DB::table('barang_penjualan')->where('barang_id', $barangId)->exists();

            if ($existsInDatabase) {
                // Ambil jumlah barang dari tabel pivot
                $jumlahPivot = DB::table('barang_penjualan')
                    ->where('barang_id', $barangId)
                    ->value('jumlah_itemporary');

                if ($jumlahPivot !== null) {
                    // Hapus barang dari tabel pivot
                    $deleted = DB::table('barang_penjualan')->where('barang_id', $barangId)->delete();

                    if ($deleted) {
                        // Kurangi jumlah barang di tabel barang
                        DB::table('barang')
                            ->where('id', $barangId)
                            ->increment('jumlah', $jumlahPivot);

                        return response()->json([
                            'status' => 'success',
                            'message' => 'Barang berhasil dihapus dari database, dan jumlah barang diperbarui.',
                        ]);
                    } else {
                        return response()->json([
                            'status' => 'error',
                            'message' => 'Gagal menghapus barang dari database.',
                        ], 500);
                    }
                } else {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Jumlah barang di tabel pivot tidak ditemukan.',
                    ], 500);
                }
            } else {
                // Barang tidak ada di database, hapus dari sesi
                $data = Session::get('edit_penjualan_barang', []);
                if (isset($data[$barangId])) {
                    unset($data[$barangId]); // Hapus barang berdasarkan ID
                    Session::put('edit_penjualan_barang', $data); // Update sesi

                    return response()->json([
                        'status' => 'success',
                        'message' => 'Barang berhasil dihapus dari sesi.',
                    ]);
                }

                return response()->json([
                    'status' => 'error',
                    'message' => 'Barang tidak ditemukan di sesi atau database.',
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal menghapus barang: ' . $e->getMessage(),
            ], 500);
        }

        // $data = Session::get('edit_penjualan_barang', []);
        // unset($data[$request->id]); // Hapus barang berdasarkan ID
        // Session::put('edit_penjualan_barang', $data); // Update sesi

        return response()->json(['message' => 'Barang berhasil dihapus dari sesi']);
    }

    public function hapusSemuaSesi()
    {
        // Hapus semua data sesi yang terkait
        session()->forget('penjualan_barang'); // Ganti dengan nama sesi yang Anda gunakan
        session()->forget('edit_penjualan_barang'); // Ganti dengan nama sesi yang Anda gunakan
        return redirect()->route('penjualan');
    }

    public function update(Request $request, $id)
    {
        // Panggil method `updatePenjualan` dari model
        $result = Penjualan::updatePenjualan($request, $id);

        // Jika terjadi error, arahkan sesuai dengan kondisi
        if ($result['status'] == 'error') {
            $route = isset($result['redirect_route']) ? $result['redirect_route'] : 'penjualan.index';
            return redirect()->route($route)->with('error', $result['message'] ?? 'Terjadi kesalahan.');
        }

        // Jika berhasil, arahkan ke halaman index dengan pesan sukses
        return redirect()->to('penjualan')->with('success', $result['message']);
    }




    public function laporan()
    {
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $penjualan = DB::table('penjualan as p')
            ->select([
                'p.id',
                'p.tanggal_transaksi',
                'p.bayar',
                'p.kembali',
                DB::raw('string_agg(b.nama, \', \') AS barang_nama'),
                DB::raw('SUM(bp.jumlah) AS total_item'),
                DB::raw('SUM(bp.harga) AS total_harga'),
                'c.nama as nama_customer',
                'u.name as nama_user'
            ])
            ->join('barang_penjualan as bp', 'p.id', '=', 'bp.penjualan_id')
            ->join('barang as b', 'bp.barang_id', '=', 'b.id')
            ->join('customer as c', 'p.customer_id', '=', 'c.id')
            ->join('user as u', 'p.user_id', '=', 'u.id')  // Menggunakan 'user' bukan 'users'
            ->whereBetween('p.tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->groupBy('p.id', 'p.tanggal_transaksi', 'p.bayar', 'p.kembali', 'c.nama', 'u.name')
            ->orderByDesc('p.tanggal_transaksi')
            ->get();

        $penjualanDetail = Penjualan::join('user', 'penjualan.user_id', '=', 'user.id')
            ->leftJoin('customer', 'penjualan.customer_id', '=', 'customer.id') // Join dengan tabel customer
            ->select('penjualan.*', 'user.name as user_nama', 'customer.nama as customer_nama') // Pilih nama customer
            ->orderBy('penjualan.tanggal_transaksi', 'desc')->get();

        return view('penjualan.laporanPenjualan', compact('penjualan', 'penjualanDetail'));
    }
}
