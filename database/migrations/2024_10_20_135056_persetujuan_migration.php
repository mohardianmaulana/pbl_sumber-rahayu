<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('persetujuan', function (Blueprint $table) {
            // Kolom 'id' sebagai primary key dan auto increment
            $table->bigIncrements('id');

            // Kolom foreign key dengan referensi ke tabel lain (supplier, customer, kategori, barang, user)
            $table->unsignedBigInteger('supplier_id')->nullable();
            $table->unsignedBigInteger('customer_id')->nullable();
            $table->unsignedBigInteger('kategori_id')->nullable();
            $table->unsignedBigInteger('barang_id')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();

            // Enum untuk kolom 'kerjaAksi'
            $table->enum('kerjaAksi', ['create', 'update', 'delete', 'recovey']);

            // Kolom 'namaTabel' tipe text
            $table->text('namaTabel');

            // Kolom 'lagiProses' tipe tinyint
            $table->boolean('lagiProses');

            // Kolom 'kodePersetujuan' tipe text (nullable)
            $table->text('kodePersetujuan')->nullable();

            // Relasi foreign key ke tabel lain (opsional, sesuaikan dengan tabel yang ada)
            $table->foreign('supplier_id')->references('id')->on('supplier');
            $table->foreign('customer_id')->references('id')->on('customer');
            $table->foreign('kategori_id')->references('id')->on('kategori');
            $table->foreign('barang_id')->references('id')->on('barang');
            $table->foreign('user_id')->references('id')->on('user');

            // Timestamps otomatis untuk created_at dan updated_at
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('persetujuan');
    }
}
;