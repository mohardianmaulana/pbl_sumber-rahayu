<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class Penjualan extends Model
{
    use HasFactory;
    protected $table = 'penjualan';
    protected $fillable = ['total_item', 'total_harga', 'tanggal_transaksi', 'bayar', 'kembali', 'user_id', 'customer_id'];
    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'barang_penjualan')
            ->withPivot('jumlah', 'harga', 'jumlah_itemporary')
            ->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFormattedTanggalTransaksiAttribute()
    {
        return Carbon::parse($this->attributes['tanggal_transaksi'])->format('d-m-Y');
    }

    public static function tampil()
    {
        $today = Carbon::today();

        // Ambil data penjualan hari ini
        $penjualan = Penjualan::join('user', 'penjualan.user_id', '=', 'user.id')
            ->leftJoin('customer', 'penjualan.customer_id', '=', 'customer.id') // Join dengan tabel customer
            ->select('penjualan.*', 'user.name as user_nama', 'customer.nama as customer_nama') // Pilih nama customer
            ->whereDate('penjualan.tanggal_transaksi', '=', $today)
            ->orderBy('penjualan.tanggal_transaksi', 'desc')
            ->get();

        // Ambil data customer yang statusnya 1
        $customers = Customer::where('status', 1)->get();

        // Kembalikan kedua data sebagai array
        return [
            'penjualan' => $penjualan,
            'customers' => $customers,
        ];
    }

    public static function tampilLama()
    {
        $today = Carbon::today();

        $penjualan = Penjualan::join('user', 'penjualan.user_id', '=', 'user.id')
            ->leftJoin('customer', 'penjualan.customer_id', '=', 'customer.id') // Join dengan tabel customer
            ->select('penjualan.*', 'user.name as user_nama', 'customer.nama as customer_nama') // Pilih nama customer
            ->whereDate('penjualan.tanggal_transaksi', '<', $today)
            ->orderBy('penjualan.tanggal_transaksi', 'desc')
            ->get();

        return $penjualan;
    }

    public static function tambah($customer_id)
    {
        // Ambil semua barang yang tidak memiliki QR code dan join dengan tabel harga_barang
        $barang = Barang::leftJoin('harga_barang', 'barang.id', '=', 'harga_barang.barang_id')
            ->select(
                'barang.*',
                'harga_barang.harga_beli',
                'harga_barang.harga_jual'
            )
            ->whereNull('barang.id_qr')
            ->whereNull('harga_barang.tanggal_selesai') // Hanya ambil harga yang belum selesai
            ->get();

        // Ambil rata-rata harga beli untuk barang yang tidak memiliki tanggal_selesai
        $avgHargaBeli = DB::table('harga_barang')
            ->select('barang_id', DB::raw('ROUND(AVG(harga_beli)) as rata_rata_harga_beli'))
            ->whereNull('tanggal_selesai')
            ->groupBy('barang_id')
            ->get();

        // Menyimpan hasil rata-rata ke dalam array
        $rataRataHargaBeli = [];
        foreach ($avgHargaBeli as $avg) {
            $rataRataHargaBeli[$avg->barang_id] = $avg->rata_rata_harga_beli;
        }

        // Ambil data customer berdasarkan ID
        $customer = Customer::find($customer_id);

        // Kembalikan data yang dibutuhkan sebagai array
        return [
            'barang' => $barang,
            'customer' => $customer,
        ];
    }

    public static function tambahPenjualan($request)
    {
        // Validasi data request
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|array|min:1',
            'barang_id.*' => 'required|exists:barang,id',
            'harga_jual.*' => 'required|numeric|min:1',
            'jumlah.*' => 'required|numeric|min:1',
            'bayar' => 'required|numeric|min:1',
        ], [
            'barang_id.required' => 'Harus memilih setidaknya satu barang',
            'barang_id.*.required' => 'Barang tidak valid',
            'harga_jual.*.required' => 'Harga jual wajib diisi',
            'harga_jual.*.numeric' => 'Harga jual harus berupa angka',
            'harga_jual.*.min' => 'Harga jual tidak boleh kurang dari 1',
            'jumlah.*.required' => 'Jumlah wajib diisi',
            'jumlah.*.numeric' => 'Jumlah harus berupa angka',
            'jumlah.*.min' => 'Jumlah tidak boleh kurang dari 1',
            'bayar.required' => 'Bayar wajib diisi',
            'bayar.numeric' => 'Bayar harus berupa angka',
            'bayar.min' => 'Bayar tidak boleh kurang dari 1',
        ]);

        // Jika validasi gagal, kembalikan error
        if ($validator->fails()) {
            return [
                'status' => 'error',
                'errors' => $validator->errors(),
            ];
        }

        // Hitung total harga dan total item
        $totalHarga = 0;
        $totalItem = 0;

        foreach ($request->harga_jual as $index => $harga) {
            $jumlah = $request->jumlah[$index];
            $totalHarga += $harga * $jumlah;
            $totalItem += $jumlah;
        }

        // Hitung kembalian
        $bayar = $request->bayar;
        $kembali = $bayar - $totalHarga;

        // Simpan ke tabel penjualan
        $penjualan = Penjualan::create([
            'customer_id' => $request->customer_id,
            'total_item' => $totalItem,
            'total_harga' => $totalHarga,
            'bayar' => $bayar,
            'kembali' => $kembali,
            'tanggal_transaksi' => now(),
            'user_id' => Auth::id(),
        ]);

        // Simpan detail barang dan update stok
        foreach ($request->barang_id as $index => $barang_id) {
            $harga = $request->harga_jual[$index];
            $jumlah = $request->jumlah[$index];

            // Simpan data barang yang dijual
            $penjualan->barangs()->attach($barang_id, [
                'jumlah' => $jumlah,
                'harga' => $harga,
                'jumlah_itemporary' => $jumlah,
            ]);

            // Update stok barang
            $barang = Barang::find($barang_id);
            $barang->jumlah -= $jumlah;
            $barang->save();
        }

        session()->forget('penjualan_barang');

        // Jika berhasil, kembalikan status sukses
        return [
            'status' => 'success',
        ];
    }

    public static function edit($id)
    {
        // Ambil data penjualan beserta relasi barangs, customer, dan user
        $penjualan = Penjualan::with(['barangs', 'customer', 'user'])->find($id);

        $dataBarang = Session()->get('edit_penjualan_barang', []);


        if (!$penjualan) {
            return [
                'status' => 'error',
                'message' => 'Penjualan tidak ditemukan.',
            ];
        }

        // Cek apakah tanggal transaksi lebih dari satu bulan yang lalu
        $tanggalTransaksi = Carbon::parse($penjualan->tanggal_transaksi);
        $satuBulanLalu = Carbon::now()->subMonth();

        if ($tanggalTransaksi->lt($satuBulanLalu)) {
            return [
                'status' => 'error',
                'message' => 'Penjualan lebih dari satu bulan tidak dapat diedit.',
                'redirect_route' => 'penjualan.lama',
            ];
        }

        // Ambil semua barang yang tidak memiliki QR code dan join dengan tabel harga_barang
        $barangs = Barang::leftJoin('harga_barang', 'barang.id', '=', 'harga_barang.barang_id')
            ->select(
                'barang.*',
                'harga_barang.harga_beli',
                'harga_barang.harga_jual'
            )
            ->whereNull('barang.id_qr')
            ->whereNull('harga_barang.tanggal_selesai') // Hanya ambil harga yang belum selesai
            ->get();

        // Gabungkan data pivot dan sesi
        $dataPivot = $penjualan->barangs->map(function ($barang) {
            return [
                'id' => $barang->id,
                'nama' => $barang->nama,
                'harga' => $barang->pivot->harga, // Data dari pivot
                'jumlah' => $barang->pivot->jumlah, // Data jumlah dari pivot
            ];
        })->toArray(); // Ambil data pivot barang
        $dataFinal = array_merge($dataPivot, $dataBarang);

        // Pastikan semua elemen di $dataFinal memiliki struktur konsisten
        $dataFinal = array_map(function ($item) {
            return is_array($item) ? $item : [];
        }, $dataFinal);

        // Jika berhasil, kembalikan data yang diperlukan
        return [
            'status' => 'success',
            'dataFinal' => $dataFinal,
            'dataBarang' => $dataBarang,
            'penjualan' => $penjualan,
            'barangs' => $barangs,
        ];
    }

    public static function updatePenjualan($data, $id)
    {
        // Cari data penjualan berdasarkan ID
        $penjualan = Penjualan::find($id);

        if (!$penjualan) {
            return [
                'status' => 'error',
                'message' => 'Penjualan tidak ditemukan.',
            ];
        }

        // Cek apakah tanggal transaksi lebih dari satu bulan yang lalu
        $tanggalTransaksi = Carbon::parse($penjualan->tanggal_transaksi);
        $satuBulanLalu = Carbon::now()->subMonth();

        if ($tanggalTransaksi->lt($satuBulanLalu)) {
            return [
                'status' => 'error',
                'message' => 'Penjualan lebih dari satu bulan tidak dapat diedit.',
            ];
        }

        // Hitung total harga dan total item
        $totalHarga = 0;
        $totalItem = 0;

        foreach ($data['harga_jual'] as $index => $harga) {
            $jumlah = $data['jumlah'][$index];
            $totalHarga += $harga * $jumlah;
            $totalItem += $jumlah;
        }

        // Hitung kembali nilai bayar dan kembali
        $bayar = $data['bayar'];
        $kembali = $bayar - $totalHarga;

        // Update data penjualan
        $penjualan->update([
            'total_item' => $totalItem,
            'total_harga' => $totalHarga,
            'bayar' => $bayar,
            'kembali' => $kembali,
            'tanggal_transaksi' => $penjualan->tanggal_transaksi,
            'user_id' => auth()->id(),
        ]);

        // Sinkronisasi data ke tabel pivot barang_penjualan
        foreach ($data['barang_id'] as $index => $barang_id) {
            $harga = $data['harga_jual'][$index];
            $jumlah = $data['jumlah'][$index];

            // Ambil data lama dari tabel pivot barang_penjualan
            $pivotData = DB::table('barang_penjualan')
                ->where('barang_id', $barang_id)
                ->where('penjualan_id', $penjualan->id)
                ->first();

            $jumlah_itemporary = $pivotData ? $pivotData->jumlah_itemporary : 0;

            if ($pivotData) {
                // Perbarui jumlah barang di pivot table
                $penjualan->barangs()->updateExistingPivot($barang_id, [
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'jumlah_itemporary' => $jumlah,
                ]);

                // Logika penyesuaian stok barang
                $barang = Barang::find($barang_id);
                if ($jumlah < $jumlah_itemporary) {
                    $selisihJumlah = $jumlah_itemporary - $jumlah;
                    $barang->jumlah += $selisihJumlah;
                } elseif ($jumlah > $jumlah_itemporary) {
                    $selisihJumlah = $jumlah - $jumlah_itemporary;
                    $barang->jumlah -= $selisihJumlah;
                }

                // Simpan perubahan stok barang jika ada
                if ($barang->isDirty('jumlah')) {
                    $barang->save();
                }
            } else {
                // Jika tidak ada data lama, tambahkan data baru ke pivot table
                $penjualan->barangs()->attach($barang_id, [
                    'jumlah' => $jumlah,
                    'harga' => $harga,
                    'jumlah_itemporary' => $jumlah,
                ]);

                // Kurangi stok barang baru
                $barang = Barang::find($barang_id);
                $barang->jumlah -= $jumlah;
                $barang->save();
            }
        }

        return [
            'status' => 'success',
            'message' => 'Penjualan berhasil diperbarui.',
        ];
    }
}
