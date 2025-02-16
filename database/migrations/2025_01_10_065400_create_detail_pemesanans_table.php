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
        Schema::create('detail_pemesanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pemesanan_id');
            $table->unsignedBigInteger('pembayaran_id');
            $table->unsignedBigInteger('tiket_id');
            $table->unsignedBigInteger('wisata_id');
            $table->string('kode_tiket');
            $table->date('tanggal_berlaku');
            $table->string('notifikasi');
            $table->enum('status', ['proses', 'selesai', 'batal'])->default('proses');
            $table->timestamps();

            $table->foreign('pemesanan_id')->references('id')->on('pemesanans')->onDelete('cascade');
            $table->foreign('pembayaran_id')->references('id')->on('pembayarans')->onDelete('cascade');
            $table->foreign('tiket_id')->references('id')->on('tikets')->onDelete('cascade');
            $table->foreign('wisata_id')->references('id')->on('wisatas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_pemesanans');
    }
};
