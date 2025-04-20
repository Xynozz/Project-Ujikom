<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ulasan extends Model
{
    protected $fillable = [
        'wisata_id',
        'user_id',
        'rating',
        'ulasan',
    ];

    public function wisata()
    {
        return $this->belongsTo(Wisata::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
