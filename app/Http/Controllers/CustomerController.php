<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Persetujuan;
use Illuminate\Support\Facades\Validator;
use App\Models\Customer;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $customers = Customer::where('status', 1)->get();

        return view('customer.index', compact('customers'));
    }

    public function arsip(Request $request)
    {
        // Mengambil data customer yang statusnya 0
        $customers = Customer::where('status', 0)->get();

        return view('customer.indexArsip', compact('customers'));
    }

    public function pulihkan($id)
    {
        $customer = Customer::pulihkan($id);

        return redirect()->route('customer.lama')->with('success', 'Customer berhasil dipulihkan.');
    }

    public function arsipkan($id)
    {
        $customer = Customer::arsipkan($id);

        return redirect()->route('customer')->with('success', 'Customer berhasil diarsipkan.');
    }

    public function create()
    {
        return view('customer.create');
    }

    public function store(Request $request)
    {
        $customer = Customer::storeCustomer($request);

        if ($customer['status'] == 'error') {
            // Jika ada error validasi, kembali ke form dengan error
            return redirect()->back()
                ->withErrors($customer['errors'])
                ->withInput();
        }

        return redirect()->to('customer')->with('success', 'Customer berhasil ditambahkan');
    }

    public function checkEdit($id)
    {
        $userId = Auth::id();

        // Memanggil method di model Persetujuan
        $result = Persetujuan::checkEditCustomer($id, $userId);

        // Memproses hasil dari model
        if (isset($result['redirect'])) {
            return redirect()->to($result['redirect'])->with($result['status'] ?? 'info', $result['message'] ?? '');
        } elseif (isset($result['view'])) {
            return view($result['view'], $result['data']);
        }
    }

    public function edit($id)
    {
        // Memanggil method getById dari model Customer
        $customer = Customer::editCustomer($id);

        // Mengirim data customer ke view
        return view('customer.edit')->with('customer', $customer);
    }

    // CustomerController.php
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'nomor' => 'required',
            'alamat' => 'required',
        ], [
            'nama.required' => 'Nama customer wajib diisi',
            'nomor.required' => 'Nomor HP wajib diisi',
            'alamat.required' => 'Alamat wajib diisi',
        ]);

        // Siapkan data customer yang akan diupdate
        $data = [
            'nama' => $request->nama,
            'nomor' => $request->nomor,
            'alamat' => $request->alamat,
        ];

        // Panggil method `updateCustomer` dari model Customer
        Customer::updateCustomer($id, $data);

        return redirect()->to('customer')->with('success', 'Customer berhasil diperbarui');
    }
}
