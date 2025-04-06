<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Wisata extends Model
{
    protected $fillable = [
        'nama_wisata',
        'deskripsi',
        'provinsi',
        'kabupaten',
        'kecamatan',
        'kelurahan',
        'gambar',
        'short_video',
        'thumbnail',
        'jam_buka',
        'jam_tutup',
        'status',
        'kategori_id',
    ];

    protected $appends = ['thumbnail_url', 'gambar_url', 'short_video_url'];

    public function getThumbnailUrlAttribute()
    {
        return url('storage/' . $this->thumbnail);
    }

    public function getGambarUrlAttribute()
    {
        return url('storage/' . $this->gambar);
    }

    public function getShortVideoUrlAttribute()
    {
        return url('storage/' . $this->short_video);
    }

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
