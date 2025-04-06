<?php

namespace App\Console\Commands;

use App\Models\Detail_pemesanan;
use Illuminate\Console\Command;
use Carbon\Carbon;

class ExpireTiketCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tiket:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nonaktifkan tiket yang sudah expired';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredTikets = Detail_pemesanan::where('expired_at', '<', Carbon::now())->update(['status' => 'expired']);
        $this->info("Total tiket expired: $expiredTikets");
    }
}
