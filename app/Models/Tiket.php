<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

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

        static::creating(function ($tiket) {
            // Generate kode tiket dengan format: TIK-YYYYMMDD-ID-RANDOM
            $tiket->kode_tiket = 'TIK-' . $tiket->wisata->nama_wisata . '-' . strtoupper(Str::random(5));
        });
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
