
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('barang_penjualan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('barang_id');
            $table->unsignedBigInteger('penjualan_id');
            $table->integer('jumlah');
            $table->integer('jumlah_itemporary');
            $table->integer('harga');
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('penjualan_id')->references('id')->on('penjualan')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('barang_penjualan');
    }
};