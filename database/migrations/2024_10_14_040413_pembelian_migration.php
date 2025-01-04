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
        Schema::create('pembelian', function (Blueprint $table) {
            $table->id();
            $table-> unsignedBigInteger('user_id');
            $table-> unsignedBigInteger('supplier_id');
            $table-> integer('total_item');
            $table-> integer('total_harga');
            $table-> boolean('status');
            $table-> date('tanggal_transaksi');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('user')->onDelete('cascade');
            $table->foreign('supplier_id')->references('id')->on('supplier')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembelian');
    }
};
