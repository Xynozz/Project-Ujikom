<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pembayaran extends Model
{
    use HasFactory;

    protected $fillable = [
        'pemesanan_id',
        'order_id',
        'status',
        'total_bayar',
        'metode_pembayaran',
        'tanggal_bayar'
    ];

    protected $casts = [
        'data_pembayaran' => 'array',
        'tanggal_pembayaran' => 'datetime'
    ];

    /**
     * Relasi ke model Pemesanan
     */
    public function pemesanan()
    {
        return $this->belongsTo(Pemesanan::class, 'pemesanan_id');
    }
}
