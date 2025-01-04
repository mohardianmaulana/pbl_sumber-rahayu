<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persetujuan extends Model
{
    protected $table = 'persetujuan';

    public $timestamps = false;

    protected $fillable = [
        'supplier_id',
        'kategori_id',
        'barang_id',
        'customer_id',
        'user_id',
        'kerjaAksi',
        'namaTabel',
        'lagiProses',
        'kodePersetujuan',
    ];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id');
    }

    public function kategori()
    {
        return $this->belongsTo(Kategori::class, 'kategori_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public static function checkEditBarang($barangId, $userId)
    {
        // Mengambil data barang berdasarkan ID
        $barang = Barang::find($barangId);
        
        // Mengatur data dasar persetujuan
        $kerjaAksi = "update";
        $namaTabel = "Barang";
        $data = [
            'supplier_id' => null,
            'customer_id' => null,
            'kategori_id' => null,
            'barang_id' => $barang->id,
            'user_id' => $userId,
            'kerjaAksi' => $kerjaAksi,
            'namaTabel' => $namaTabel,
            'lagiProses' => 0,
            'kodePersetujuan' => null,
        ];

        // Mencari persetujuan berdasarkan kondisi yang ada
        $persetujuan = self::where('barang_id', $barang->id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', $kerjaAksi)
            ->where('namaTabel', $namaTabel)
            ->first();

        // Memeriksa apakah persetujuan sudah diisi dan dalam proses
        $persetujuanIsiForm = $persetujuan && $persetujuan->kodePersetujuan !== null;
        $persetujuanDisetujui = $persetujuanIsiForm && $persetujuan->lagiProses == 1;

        // Jika persetujuan belum ada, buat persetujuan baru
        if (!$persetujuan) {
            $persetujuan = new self;
            $persetujuan->fill($data);
            $persetujuan->timestamps = false;
            $persetujuan->save();
            return [
                'redirect' => '/barang',
                'status' => 'success',
                'message' => 'Persetujuan berhasil diajukan'
            ];
        } elseif ($persetujuanDisetujui) {
            // Jika persetujuan sudah disetujui, arahkan ke halaman edit barang
            return [
                'redirect' => route('barang.edit', $barang->id)
            ];
        } elseif ($persetujuanIsiForm) {
            // Jika persetujuan sedang diproses, kirim ke view konfirmasi
            return [
                'view' => 'persetujuan.konfirmasi',
                'data' => compact('persetujuan')
            ];
        } else {
            // Jika persetujuan belum disetujui, beri pesan menunggu persetujuan
            return [
                'redirect' => '/barang',
                'status' => 'info',
                'message' => 'Tunggu persetujuan dari owner.'
            ];
        }
    }

    public static function checkEditSupplier($supplierId, $userId)
    {
        // Mengambil data barang berdasarkan ID
        $supplier = Supplier::find($supplierId);
        
        // Mengatur data dasar persetujuan
        $kerjaAksi = "update";
        $namaTabel = "Supplier";
        $data = [
            'supplier_id' => $supplier->id,
            'customer_id' => null,
            'kategori_id' => null,
            'barang_id' => null,
            'user_id' => $userId,
            'kerjaAksi' => $kerjaAksi,
            'namaTabel' => $namaTabel,
            'lagiProses' => 0,
            'kodePersetujuan' => null,
        ];

        // Mencari persetujuan berdasarkan kondisi yang ada
        $persetujuan = self::where('supplier_id', $supplier->id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', $kerjaAksi)
            ->where('namaTabel', $namaTabel)
            ->first();

        // Memeriksa apakah persetujuan sudah diisi dan dalam proses
        $persetujuanIsiForm = $persetujuan && $persetujuan->kodePersetujuan !== null;
        $persetujuanDisetujui = $persetujuanIsiForm && $persetujuan->lagiProses == 1;

        // Jika persetujuan belum ada, buat persetujuan baru
        if (!$persetujuan) {
            $persetujuan = new self;
            $persetujuan->fill($data);
            $persetujuan->timestamps = false;
            $persetujuan->save();
            return [
                'redirect' => '/supplier',
                'status' => 'success',
                'message' => 'Persetujuan berhasil diajukan'
            ];
        } elseif ($persetujuanDisetujui) {
            // Jika persetujuan sudah disetujui, arahkan ke halaman edit barang
            return [
                'redirect' => route('supplier.edit', $supplier->id)
            ];
        } elseif ($persetujuanIsiForm) {
            // Jika persetujuan sedang diproses, kirim ke view konfirmasi
            return [
                'view' => 'persetujuan.konfirmasi',
                'data' => compact('persetujuan')
            ];
        } else {
            // Jika persetujuan belum disetujui, beri pesan menunggu persetujuan
            return [
                'redirect' => '/supplier',
                'status' => 'info',
                'message' => 'Tunggu persetujuan dari owner.'
            ];
        }
    }

    public static function checkEditCustomer($customerId, $userId)
    {
        // Mengambil data barang berdasarkan ID
        $customer = Customer::find($customerId);
        
        // Mengatur data dasar persetujuan
        $kerjaAksi = "update";
        $namaTabel = "Customer";
        $data = [
            'supplier_id' => null,
            'customer_id' => $customer->id,
            'kategori_id' => null,
            'barang_id' => null,
            'user_id' => $userId,
            'kerjaAksi' => $kerjaAksi,
            'namaTabel' => $namaTabel,
            'lagiProses' => 0,
            'kodePersetujuan' => null,
        ];

        // Mencari persetujuan berdasarkan kondisi yang ada
        $persetujuan = self::where('customer_id', $customer->id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', $kerjaAksi)
            ->where('namaTabel', $namaTabel)
            ->first();

        // Memeriksa apakah persetujuan sudah diisi dan dalam proses
        $persetujuanIsiForm = $persetujuan && $persetujuan->kodePersetujuan !== null;
        $persetujuanDisetujui = $persetujuanIsiForm && $persetujuan->lagiProses == 1;

        // Jika persetujuan belum ada, buat persetujuan baru
        if (!$persetujuan) {
            $persetujuan = new self;
            $persetujuan->fill($data);
            $persetujuan->timestamps = false;
            $persetujuan->save();
            return [
                'redirect' => '/customer',
                'status' => 'success',
                'message' => 'Persetujuan berhasil diajukan'
            ];
        } elseif ($persetujuanDisetujui) {
            // Jika persetujuan sudah disetujui, arahkan ke halaman edit barang
            return [
                'redirect' => route('customer.edit', $customer->id)
            ];
        } elseif ($persetujuanIsiForm) {
            // Jika persetujuan sedang diproses, kirim ke view konfirmasi
            return [
                'view' => 'persetujuan.konfirmasi',
                'data' => compact('persetujuan')
            ];
        } else {
            // Jika persetujuan belum disetujui, beri pesan menunggu persetujuan
            return [
                'redirect' => '/customer',
                'status' => 'info',
                'message' => 'Tunggu persetujuan dari owner.'
            ];
        }
    }

    public static function checkEditKategori($kategoriId, $userId)
    {
        // Mengambil data barang berdasarkan ID
        $kategori = Kategori::find($kategoriId);
        
        // Mengatur data dasar persetujuan
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

        // Mencari persetujuan berdasarkan kondisi yang ada
        $persetujuan = self::where('kategori_id', $kategori->id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', $kerjaAksi)
            ->where('namaTabel', $namaTabel)
            ->first();

        // Memeriksa apakah persetujuan sudah diisi dan dalam proses
        $persetujuanIsiForm = $persetujuan && $persetujuan->kodePersetujuan !== null;
        $persetujuanDisetujui = $persetujuanIsiForm && $persetujuan->lagiProses == 1;

        // Jika persetujuan belum ada, buat persetujuan baru
        if (!$persetujuan) {
            $persetujuan = new self;
            $persetujuan->fill($data);
            $persetujuan->timestamps = false;
            $persetujuan->save();
            return [
                'redirect' => '/kategori',
                'status' => 'success',
                'message' => 'Persetujuan berhasil diajukan'
            ];
        } elseif ($persetujuanDisetujui) {
            // Jika persetujuan sudah disetujui, arahkan ke halaman edit barang
            return [
                'redirect' => route('kategori.edit', $kategori->id)
            ];
        } elseif ($persetujuanIsiForm) {
            // Jika persetujuan sedang diproses, kirim ke view konfirmasi
            return [
                'view' => 'persetujuan.konfirmasi',
                'data' => compact('persetujuan')
            ];
        } else {
            // Jika persetujuan belum disetujui, beri pesan menunggu persetujuan
            return [
                'redirect' => '/kategori',
                'status' => 'info',
                'message' => 'Tunggu persetujuan dari owner.'
            ];
        }
    }
}
