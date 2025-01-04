<?php

namespace App\Http\Controllers;

use App\Models\Persetujuan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersetujuanController extends Controller
{
    public function verify(Request $request)
    {
        $kode = $request->input('kode');
        $userId = Auth::id();
    
        $persetujuan = Persetujuan::where('kodePersetujuan', $kode)
            ->where('user_id', $userId)
            ->first();
    
        if ($persetujuan) {
            $persetujuan->lagiProses = 1;
            $persetujuan->save();
            
            if ($persetujuan->barang_id) {
                return redirect()->route('barang.edit', $persetujuan->barang_id)->with('success', 'Kode berhasil diverifikasi.');
            } elseif ($persetujuan->supplier_id) {
                return redirect()->route('supplier.edit', $persetujuan->supplier_id)->with('success', 'Kode berhasil diverifikasi.');
            } elseif ($persetujuan->kategori_id) {
                return redirect()->route('kategori.edit', $persetujuan->kategori_id)->with('success', 'Kode berhasil diverifikasi.');
            } elseif ($persetujuan->customer_id) {
                return redirect()->route('customer.edit', $persetujuan->customer_id)->with('success', 'Kode berhasil diverifikasi.');
            } else {
                return redirect()->back()->with('error', 'Tidak dapat menemukan jenis persetujuan.');
            }
        } else {
            return redirect()->back()->with('error', 'Kode tidak valid.');
        }
    }
    
    public function index(Request $request)
    {
        $persetujuan = Persetujuan::select('persetujuan.*', 'user.name as user_nama')
            ->leftJoin('user', 'persetujuan.user_id', '=', 'user.id')
            ->leftJoin('barang', 'persetujuan.barang_id', '=', 'barang.id')
            ->leftJoin('kategori', 'persetujuan.kategori_id', '=', 'kategori.id')
            ->leftJoin('supplier', 'persetujuan.supplier_id', '=', 'supplier.id')
            ->leftJoin('customer', 'persetujuan.customer_id', '=', 'customer.id')
            ->select(
                'persetujuan.*',
                'user.name as user_nama',
                'barang.nama as barang_nama',
                'kategori.nama_kategori as kategori_nama',
                'supplier.nama as supplier_nama',
                'customer.nama as customer_nama'
            )
            ->get();

        return view('persetujuan.index', compact('persetujuan'));
    }

    public function destroy($id)
    {
        Persetujuan::where('id', $id)->delete();
        return redirect()->to('persetujuan')->with('success', 'Berhasil menghapus data persetujuan');
    }
}
