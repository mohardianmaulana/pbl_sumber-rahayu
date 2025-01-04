<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persetujuan;
use Illuminate\Support\Facades\Validator;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupplierController extends Controller
{
    public function index(Request $request)
    {
        $supplier = Supplier::tampil();
        return view('supplier.index', compact('supplier'));
    }

    public function arsip(Request $request)
    {
        // Mengambil data kategori yang statusnya 0
        $supplier = Supplier::tampilArsip();

        return view('supplier.indexArsip', compact('supplier'));
    }

    public function pulihkan($id)
    {
        $supplier = Supplier::pulih($id);

        return redirect()->route('supplier.lama')->with('success', 'supplier berhasil dipulihkan.');
    }

    public function arsipkan($id)
    {
        $supplier = Supplier::arsip($id);

        return redirect()->route('supplier')->with('success', 'supplier berhasil diarsipkan.');
    }

    public function create()
    {
        return view('supplier.create');
    }

    public function store(Request $request)
    {
        $result = Supplier::tambah($request);

        // Memeriksa status hasil dari model
        if ($result['status'] == 'error') {
            // Jika ada error validasi, kembali ke form dengan error
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        //jika menunjukan hasil berhasil
        return redirect()->to('supplier')->with('success', 'Supplier berhasil ditambahkan');
    }

    public function checkEdit($id)
    {
        $userId = Auth::id();

        // Memanggil method di model Persetujuan
        $result = Persetujuan::checkEditSupplier($id, $userId);

        // Memproses hasil dari model
        if (isset($result['redirect'])) {
            return redirect()->to($result['redirect'])->with($result['status'] ?? 'info', $result['message'] ?? '');
        } elseif (isset($result['view'])) {
            return view($result['view'], $result['data']);
        }
    }

    public function edit($id)
    {
        $supplier = Supplier::where('id', $id)->first();
        return view('supplier.edit')->with('supplier', $supplier);
    }

    public function update(Request $request, $id)
    {
        // Memanggil method di model Barang untuk melakukan update
        $result = Supplier::ganti($request, $id);

        // Memeriksa status hasil dari model
        if ($result['status'] == 'error') {
            // Jika ada error validasi, kembali ke form dengan error
            return redirect()->back()
                ->withErrors($result['errors'])
                ->withInput();
        }

        // Jika update berhasil, redirect ke halaman barang dengan pesan sukses
        return redirect()->to('supplier')->with('success', $result['message']);
    }

    public function profil($kode)
    {
        $supplier = Supplier::where('kode', $kode)->get();
        return view('supplier.profil')->with('supplier', $supplier);
    }
}
