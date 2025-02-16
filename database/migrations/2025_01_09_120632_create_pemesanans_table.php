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
        Schema::create('pemesanans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('tiket_id');
            $table->unsignedBigInteger('wisata_id');
            $table->date('tanggal_pemesanan');
            $table->integer('jumlah_tiket');
            $table->string('total_harga');
            $table->enum('status', ['proses', 'selesai', 'batal'])->default('proses')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('tiket_id')->references('id')->on('tikets')->onDelete('cascade');
            $table->foreign('wisata_id')->references('id')->on('wisatas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pemesanans');
    }
};
