<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    protected $fillable = [
        'user_id',
        'tiket_id',
        'wisata_id',
        'tanggal_pemesanan',
        'jumlah_tiket',
        'total_harga',
        'status',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class);
    }

    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // App/Models/Pemesanan.php
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class);
    }

    public function detail_pemesanan()
    {
        return $this->hasMany(Detail_pemesanan::class);
    }
}
