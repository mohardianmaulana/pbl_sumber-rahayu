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
        Schema::create('harga_barang', function (Blueprint $table) {
            $table->id();
            $table-> unsignedBigInteger('barang_id');
            $table-> unsignedBigInteger('supplier_id');
            $table-> integer('harga_beli');
            $table-> integer('harga_jual')->nullable();
            $table-> date('tanggal_mulai');
            $table-> date('tanggal_selesai')->nullable();
            $table->timestamps();

            $table->foreign('barang_id')->references('id')->on('barang')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('supplier')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('harga_barang');
    }
};
