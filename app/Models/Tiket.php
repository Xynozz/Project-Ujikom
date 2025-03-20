<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Tiket extends Model
{
    protected $fillable = [
        'wisata_id',
        'harga_tiket',
        'kode_tiket',
    ];

// Generate kode tiket saat tiket dibuat
    protected static function boot()
    {
        parent::boot();

        // Generate kode tiket saat tiket dibuat
        static::creating(function ($tiket) {
            $tiket->kode_tiket = self::generateKodeTiket($tiket->wisata->nama_wisata);
        });

        // Update kode tiket saat wisata_id berubah
        static::updating(function ($tiket) {
            if ($tiket->isDirty('wisata_id')) {
                $tiket->kode_tiket = self::generateKodeTiket($tiket->wisata->nama_wisata);
            }
        });
    }

    // Generate kode tiket
    protected static function generateKodeTiket($namaWisata)
    {
        $date = Carbon::now();
        $year = $date->format('y');
        $month = $date->format('m');

        // Ambil 3 huruf pertama dari nama wisata dan ubah ke uppercase
        $wisataCode = strtoupper(substr($namaWisata, 0, 3));

        // Generate random number
        $random = strtoupper(Str::random(3));

        // Format: TKT-XXX-YYMM-RRR
        return "TKT-{$wisataCode}-{$year}{$month}-{$random}";
    }

    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }

    public function detail_pemesanan()
    {
        return $this->hasMany(Detail_pemesanan::class);
    }
}
