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
        // Ambil data barang dari request
        $barang = [
            'id' => $request->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
        ];

        // Simpan data ke sesi
        $data = Session::get('penjualan_barang', []); // Ambil data sesi jika ada
        $data[$request->id] = $barang; // Tambahkan atau update data barang berdasarkan ID
        Session::put('penjualan_barang', $data); // Simpan kembali ke sesi

        return response()->json(['message' => 'Barang berhasil ditambahkan ke sesi', 'data' => $data]);
    }

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
        // Ambil data barang dari request
        $barang = [
            'id' => $request->id,
            'nama' => $request->nama,
            'harga' => $request->harga,
        ];
        
        // Simpan data ke sesi
        $data = Session::get('edit_penjualan_barang', []); // Ambil data sesi jika ada
        $data[$request->id] = $barang; // Tambahkan atau update data barang berdasarkan ID
        Session::put('edit_penjualan_barang', $data); // Simpan kembali ke sesi
        
        return response()->json(['message' => 'Barang berhasil ditambahkan ke sesi', 'data' => $data]);
    }
    
    public function editHapusSesi(Request $request)
    {
        
        $data = Session::get('edit_penjualan_barang', []);
        unset($data[$request->id]); // Hapus barang berdasarkan ID
        Session::put('edit_penjualan_barang', $data); // Update sesi
        
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
        // Get the start and end of the current month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();

        $penjualan = DB::table('penjualan')
            ->join('barang_penjualan', 'penjualan.id', '=', 'barang_penjualan.penjualan_id')
            ->join('barang', 'barang_penjualan.barang_id', '=', 'barang.id')
            ->join('customer', 'penjualan.customer_id', '=', 'customer.id')
            ->join('user', 'penjualan.user_id', '=', 'user.id')
            ->select(
                'penjualan.*',
                'barang.nama as barang_nama',
                'barang_penjualan.jumlah as total_item',
                'barang_penjualan.harga as total_harga',
                'customer.nama as nama_customer',
                'user.name as nama_user'
            )
            ->whereBetween('penjualan.tanggal_transaksi', [$startOfMonth, $endOfMonth])
            ->get();

        return view('penjualan.laporanPenjualan', compact('penjualan'));
    }
}
