<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'pembayarans';

    protected $fillable = [
        'pemesanan_id',
        'order_id',
        'metode_pembayaran',
        'tanggal_pembayaran',
        'status'
    ];

    protected $casts = [
        'tanggal_pembayaran' => 'datetime',
    ];

    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class);
    }

    public function detail_pemesanan()
    {
        return $this->hasMany(Detail_pemesanan::class);
    }
}
