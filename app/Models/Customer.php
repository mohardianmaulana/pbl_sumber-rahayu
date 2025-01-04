<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class Customer extends Model
{
    use HasFactory;
    protected $table = 'customer';
    protected $fillable = ['nama', 'nomor', 'alamat', 'status'];
    protected $primaryKey = 'id';

    public static function pulihkan($id) {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->status = 1;
            $customer->save();
        }
        return $customer;
    }
    
    public static function arsipkan($id) {
        $customer = Customer::find($id);
        if ($customer) {
            $customer->status = 0;
            $customer->save();
        }
        return $customer;
    }

    public static function storeCustomer($request)
{
    $validator = Validator::make($request->all(), [
        'nama' => 'required',
        'nomor' => 'required',
        'alamat' => 'required',
    ], [
        'nama.required' => 'Nama customer wajib diisi',
        'nomor.required' => 'Nomor HP wajib diisi',
        'alamat.required' => 'Alamat wajib diisi',
    ]);

    if ($validator->fails()) {
        return [
            'status' => 'error',
            'errors' => $validator->errors(),
        ];
    }

    // Simpan data customer
    Customer::create([
        'nama' => $request->nama,
        'nomor' => $request->nomor,
        'alamat' => $request->alamat,
        'status' => 1, // Set status to 1
    ]);

    return ['status' => 'success'];
}


    // Method untuk mendapatkan customer berdasarkan ID
    public static function editCustomer($id)
    {
        return Customer::where('id', $id)->first();
    }

    // Method untuk update customer berdasarkan request dan ID
    public static function updateCustomer($id, $data)
    {
        // Update data customer
        Customer::where('id', $id)->update($data);

        // Hapus persetujuan yang sesuai
        $userId = Auth::id();
        Persetujuan::where('customer_id', $id)
            ->where('user_id', $userId)
            ->where('kerjaAksi', 'update')
            ->where('namaTabel', 'Customer')
            ->delete();
    }
    
}