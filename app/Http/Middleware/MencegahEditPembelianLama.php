<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Pembelian;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class MencegahEditPembelianLama
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $pembelian = Pembelian::find($request->route('id'));

        if ($pembelian) {
            $tanggalTransaksi = Carbon::parse($pembelian->tanggal_transaksi);
            $hariIni = Carbon::today();
            
            Log::info('Tanggal Transaksi: ' . $tanggalTransaksi->toDateString());
            Log::info('Hari Ini: ' . $hariIni->toDateString());

            if ($tanggalTransaksi->lessThan($hariIni)) {
                Log::info('Middleware triggered: Pembelian lama tidak dapat diedit.');
                return Redirect::back()->with('error', 'Pembelian lama tidak dapat diedit.');
            }
        }

        Log::info('Middleware passed: Pembelian dapat diedit.');
        return $next($request);
    }
}
