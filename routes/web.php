<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\HargaBarangController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\PembelianController;
use App\Http\Controllers\PembelianBarangBaruController;
use App\Http\Controllers\PenjualanController;
use App\Http\Controllers\PersetujuanController;
use App\Http\Controllers\UserController;


Route::middleware(['guest'])->group(function () {
    Route::get('/', [LoginController::class, 'login'])->name('login');
});

Route::post('actionlogin', [LoginController::class, 'actionlogin'])->name('actionlogin');

Route::post('actionlogout', [LoginController::class, 'actionlogout'])->name('actionlogout')->middleware('auth');

// Route::get('/register', [UserController::class, 'index'])->name('register')->middleware('can:view');

Route::get('/katalog', [BarangController::class, 'katalog'])->name('katalog');


Route::middleware('auth', 'verified')->group(function () {

    // Route::get('/user/create', [UserController::class, 'create'])->name('user.create')->middleware('can:view');
    // Route::post('/user', [UserController::class, 'store'])->name("user.store")->middleware('can:view');
    // Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name("user.edit")->middleware('can:crud');
    // Route::post('/user/{User}', [UserController::class, 'update'])->name("user.update")->middleware('can:crud');
    // Route::delete('/user/{User}', [UserController::class, 'destroy'])->name("user.destroy")->middleware('can:crud');

    //***************************************************/ BARANG /*****************************************//
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard')->middleware('can:view');
    Route::get('/kode', [DashboardController::class, 'kode'])->name('kode')->middleware('can:view');
    Route::get('/barang', [BarangController::class, 'index'])->name('admin')->middleware('can:view');
    Route::get('/barang/arsip', [BarangController::class, 'arsip'])->name('barang.lama')->middleware('can:view');
    Route::post('/barang/pulihkan/{id}', [BarangController::class, 'pulihkan'])->name('barang.pulihkan')->middleware('can:crud');
    Route::post('/barang/arsipkan/{id}', [BarangController::class, 'arsipkan'])->name('barang.arsipkan')->middleware('can:crud');
    Route::get('/scan', [BarangController::class, 'scanPage'])->name('scan');
    Route::post('/cek-qr', [BarangController::class, 'cekQrCode']);
    Route::get('/create_barang', [BarangController::class, 'create'])->name('create_barang');
    Route::get('/barang/{Barang}/edit', [BarangController::class, 'edit'])->name("barang.edit")->middleware('can:crud');
    Route::get('/barang/{Barang}/checkEdit', [BarangController::class, 'checkEdit'])->name("barang.checkEdit");
    Route::post('/barang/{Barang}', [BarangController::class, 'update'])->name("barang.update")->middleware('can:crud');
    Route::delete('/barang/{Barang}', [BarangController::class, 'destroy'])->name("barang.destroy")->middleware('can:crud');

    //***************************************************/ SUPPLIER /*****************************************//
    Route::get('supplier', [SupplierController::class, 'index'])->name('supplier')->middleware('can:view');
    Route::get('/supplier/arsip', [SupplierController::class, 'arsip'])->name('supplier.lama')->middleware('can:view');
    Route::post('/supplier/pulihkan/{id}', [SupplierController::class, 'pulihkan'])->name('supplier.pulihkan')->middleware('can:crud');
    Route::post('/supplier/arsipkan/{id}', [SupplierController::class, 'arsipkan'])->name('supplier.arsipkan')->middleware('can:crud');
    Route::get('/supplier/create', [SupplierController::class, 'create'])->name('supplier.create')->middleware('can:crud');
    Route::post('/supplier', [SupplierController::class, 'store'])->name("supplier.store")->middleware('can:crud');
    Route::get('/supplier/{Supplier}/edit', [SupplierController::class, 'edit'])->name("supplier.edit")->middleware('can:crud');
    Route::get('/supplier/{Supplier}/checkEdit', [SupplierController::class, 'checkEdit'])->name("supplier.checkEdit");
    Route::post('/supplier/{Supplier}', [SupplierController::class, 'update'])->name("supplier.update")->middleware('can:crud');
    Route::delete('/supplier/{Supplier}', [SupplierController::class, 'destroy'])->name("supplier.destroy")->middleware('can:crud');
    Route::get('/supplier/{Supplier}/profil', [SupplierController::class, 'profil'])->name('profil')->middleware('can:crud');

    //***************************************************/ SUPPLIER /*****************************************//
    Route::get('customer', [CustomerController::class, 'index'])->name('customer')->middleware('can:view');
    Route::get('/customer/arsip', [CustomerController::class, 'arsip'])->name('customer.lama')->middleware('can:view');
    Route::post('/customer/pulihkan/{id}', [CustomerController::class, 'pulihkan'])->name('customer.pulihkan')->middleware('can:crud');
    Route::post('/customer/arsipkan/{id}', [CustomerController::class, 'arsipkan'])->name('customer.arsipkan')->middleware('can:crud');
    Route::get('/customer/create', [CustomerController::class, 'create'])->name('customer.create')->middleware('can:crud');
    Route::post('/customer', [CustomerController::class, 'store'])->name("customer.store")->middleware('can:crud');
    Route::get('/customer/{customer}/edit', [CustomerController::class, 'edit'])->name("customer.edit")->middleware('can:crud');
    Route::get('/customer/{customer}/checkEdit', [CustomerController::class, 'checkEdit'])->name("customer.checkEdit");
    Route::post('/customer/{customer}', [CustomerController::class, 'update'])->name("customer.update")->middleware('can:crud');
    Route::delete('/customer/{customer}', [CustomerController::class, 'destroy'])->name("customer.destroy")->middleware('can:crud');

    //***************************************************/ KATEGORI /*****************************************//
    Route::get('/kategori', [KategoriController::class, 'index'])->name('kategori')->middleware('can:view');
    Route::get('/kategori/arsip', [KategoriController::class, 'arsip'])->name('kategori.lama')->middleware('can:view');
    Route::post('/kategori/pulihkan/{id}', [KategoriController::class, 'pulihkan'])->name('kategori.pulihkan')->middleware('can:crud');
    Route::post('/kategori/arsipkan/{id}', [KategoriController::class, 'arsipkan'])->name('kategori.arsipkan')->middleware('can:crud');
    Route::get('/kategori/create', [KategoriController::class, 'create'])->name('create')->middleware('can:crud');
    Route::post('/kategori', [KategoriController::class, 'store'])->name("store")->middleware('can:crud');
    Route::get('/kategori/{Kategori}/edit', [KategoriController::class, 'edit'])->name("kategori.edit")->middleware('can:crud');
    Route::get('/kategori/{Kategori}/checkEdit', [KategoriController::class, 'checkEdit'])->name("kategori.checkEdit");
    Route::post('/kategori/{Kategori}', [KategoriController::class, 'update'])->name("update")->middleware('can:crud');
    Route::delete('/kategori/{Kategori}', [KategoriController::class, 'destroy'])->name("destroy")->middleware('can:crud');
    // Route::get('/kategori/view/pdf', [KategoriController::class, 'view_pdf']);

    //***************************************************/ PEMBELIAN BARANG BARU /*****************************************//
    Route::get('/pembelianBarang/create', [PembelianBarangBaruController::class, 'create'])->name('create')->middleware('can:crud');
    Route::post('/pembelianBarang', [PembelianBarangBaruController::class, 'store'])->name('store')->middleware('can:crud');
    Route::get('/pembelian/{Pembelian}/edit-barang-baru', [PembelianBarangBaruController::class, 'edit'])->name('editBarangBaru')->middleware('can:crud');
    Route::put('/pembelian/{Pembelian}/update-barang-baru', [PembelianBarangBaruController::class, 'update'])->name('updateBarangBaru')->middleware('can:crud');

    //***************************************************/ PEMBELIAN /*****************************************//
    Route::get('/pembelian', [PembelianController::class, 'index'])->name('pembelian')->middleware('can:view');
    Route::get('/pembelian/lama', [PembelianController::class, 'oldPurchases'])->name('pembelian.lama')->middleware('can:view');
    Route::get('/pembelian/create', [PembelianController::class, 'create'])->name('create')->middleware('can:crud');
    Route::post('/cek-qr', [PembelianController::class, 'cekQR'])->name('cek-qr');
    Route::post('/pembelian/tambah-sesi', [PembelianController::class, 'tambahSesi'])->name('pembelian.tambahSesi');
    Route::post('/pembelian/hapus-sesi', [PembelianController::class, 'hapusSesi'])->name('pembelian.hapusSesi');
    Route::post('/pembelian', [PembelianController::class, 'store'])->name('store')->middleware('can:crud');
    Route::get('/pembelian/{Pembelian}/edit', [PembelianController::class, 'edit'])->name('edit')->middleware('can:crud');
    Route::post('/pembelian/edit-tambah-sesi', [PembelianController::class, 'editTambahSesi'])->name('pembelian.editTambahSesi');
    Route::post('/pembelian/edit-hapus-sesi', [PembelianController::class, 'editHapusSesi'])->name('pembelian.editHapusSesi');
    Route::get('/pembelian/kembali', [PembelianController::class, 'hapusSemuaSesi'])->name('pembelian.kembali');
    Route::put('/pembelian/{Pembelian}', [PembelianController::class, 'update'])->name('update')->middleware('can:crud');
    Route::get('/laporan_pembelian', [PembelianController::class, 'laporan'])->name('laporan_pembelian')->middleware('can:view');

    //***************************************************/ PENJUALAN /*****************************************//
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan')->middleware('can:view');
    Route::get('/penjualan/lama', [PenjualanController::class, 'oldPurchases'])->name('penjualan.lama')->middleware('can:view');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create')->middleware('can:crud');
    Route::get('/scan_qr', [PenjualanController::class, 'scanPage'])->name('scan_qr');
    Route::post('/cek_qr', [PenjualanController::class, 'cekQR'])->name('cek_qr');
    Route::post('/penjualan/tambah-sesi', [PenjualanController::class, 'tambahSesi'])->name('penjualan.tambahSesi');
    Route::post('/penjualan/hapus-sesi', [PenjualanController::class, 'hapusSesi'])->name('penjualan.hapusSesi');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('store')->middleware('can:crud');
    Route::get('/penjualan/{Penjualan}/edit', [PenjualanController::class, 'edit'])->name('edit')->middleware('can:crud');
    Route::post('/penjualan/edit-tambah-sesi', [PenjualanController::class, 'editTambahSesi'])->name('penjualan.editTambahSesi');
    Route::get('/penjualan/kembali', [PenjualanController::class, 'hapusSemuaSesi'])->name('penjualan.kembali');
    Route::post('/penjualan/edit-hapus-sesi', [PenjualanController::class, 'editHapusSesi'])->name('penjualan.editHapusSesi');
    Route::put('/penjualan/{Penjualan}', [PenjualanController::class, 'update'])->name('update')->middleware('can:crud');
    Route::get('/laporan_penjualan', [PenjualanController::class, 'laporan'])->name('laporan_penjualan')->middleware('can:view');

    //***************************************************/ PERSETUJUAN /*****************************************//
    Route::get('/persetujuan', [PersetujuanController::class, 'index'])->name('persetujuan')->middleware('can:persetujuan');
    Route::get('/persetujuan/{id}/generateCode', [BarangController::class, 'generateCode'])->name('generateCode')->middleware('can:persetujuan');
    Route::post('persetujuan/verify', [PersetujuanController::class, 'verify'])->name('persetujuan.verify');
    Route::delete('/persetujuan/{persetujuan}', [PersetujuanController::class, 'destroy'])->name("destroy")->middleware('can:persetujuan');

    //***************************************************/ HARGA BARANG /*****************************************//
    Route::get('/harga', [HargaBarangController::class, 'index'])->name('harga')->middleware('can:crud');
    Route::get('/harga/{id}/edit', [HargaBarangController::class, 'edit'])->name("edit")->middleware('can:crud');
    Route::post('/harga/{id}', [HargaBarangController::class, 'update'])->name("update")->middleware('can:crud');
});

// Route::get('/barang', function () {
//     return view('welcome');
// });
