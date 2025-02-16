<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $fillable = [
        'nama_wisata',
        'deskripsi',
        'lokasi',
        'gambar',
        'short_video',
        'thumbnail',
        'jam_operasional',
        'status',
        'kategori_id',
    ];

    public function kategori()
    {
        return $this->belongsTo(Kategori::class);
    }

    public function ulasan()
    {
        return $this->hasMany(Ulasan::class);
    }

    public function tiket()
    {
        return $this->hasMany(Tiket::class);
    }

    public function detail_pemesanan()
    {
        return $this->hasMany(Detail_pemesanan::class);
    }

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class);
    }
}
