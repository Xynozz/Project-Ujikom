<?php

namespace App\Mail;

use App\Models\Detail_pemesanan;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TiketAktivasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;

    public function __construct(Detail_pemesanan $detail)
    {
        $this->detail = $detail;
    }

    public function build()
    {
        return $this->subject('Tiket Anda Telah Diaktivasi')
                    ->markdown('admin.email.tiket_aktivasi');
    }
}
