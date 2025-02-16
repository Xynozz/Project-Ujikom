<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detail_pemesanan extends Model
{
    protected $fillable = [
        'pemesanan_id',
        'tiket_id',
        'wisata_id',
        'kode_tiket',
        'pembayaran_id',
        'tanggal_berlaku',
        'notifikasi',
        'status',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function pembayaran()
    {
        return $this->belongsTo(Pembayaran::class);
    }
}
