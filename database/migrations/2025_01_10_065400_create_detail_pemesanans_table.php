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
            $table->foreignId('pemesanan_id')->constrained('pemesanans')->onDelete('cascade');
            $table->foreignId('pembayaran_id')->constrained('pembayarans')->onDelete('cascade');
            $table->foreignId('wisata_id')->constrained('wisatas')->onDelete('cascade');
            $table->foreignId('tiket_id')->constrained('tikets')->onDelete('cascade');
            $table->dateTime('expired_at')->nullable();
            $table->string('qr_code')->nullable();
            $table->string('qr_path')->nullable();
            $table->enum('status', ['Expired', 'Unexpired', 'Activate'])->default('Unexpired');
            $table->timestamps();

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
