<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualan', function (Blueprint $table) {
            $table->id();
            $table-> integer('total_item');
            $table-> integer('total_harga');
            $table-> integer('bayar');
            $table-> integer('kembali');
            $table-> date('tanggal_transaksi');
            $table-> unsignedBigInteger('user_id');
            $table-> unsignedBigInteger('customer_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('customer_id')->references('id')->on('customer')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualan');
    }
};