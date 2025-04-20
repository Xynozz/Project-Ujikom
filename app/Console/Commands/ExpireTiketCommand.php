<?php

namespace App\Console\Commands;

use App\Models\Detail_pemesanan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ExpireTiketCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'tiket:expire';

    /**
     * The console command description.
     */
    protected $description = 'Nonaktifkan tiket yang sudah expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $now = Carbon::now();

            $expiredTikets = Detail_pemesanan::where('expired_at', '<', $now)
                ->where('status', '!=', 'expired') // hanya update jika belum expired
                ->update(['status' => 'expired']);

            $this->info("Total tiket yang diubah menjadi expired: $expiredTikets");
            Log::info("ExpireTiketCommand: $expiredTikets tiket diubah menjadi expired pada $now.");
        } catch (\Exception $e) {
            Log::error("Gagal menjalankan ExpireTiketCommand: " . $e->getMessage());
            $this->error("Terjadi kesalahan saat menjalankan perintah: " . $e->getMessage());
        }
    }
}
