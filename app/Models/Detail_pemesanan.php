<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Crypt;
// Tambahkan untuk enkripsi

class Detail_pemesanan extends Model
{
    protected $fillable = [
        'pemesanan_id',
        'tiket_id',
        'wisata_id',
        'pembayaran_id',
        'expired_at',
        'qr_code',
        'qr_path',
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
