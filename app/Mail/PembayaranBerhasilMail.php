<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PembayaranBerhasilMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pemesanan;
    public $detail;

    public function __construct($pemesanan, $detail)
    {
        $this->pemesanan = $pemesanan;
        $this->detail = $detail;
    }

    public function build()
    {
        return $this->subject('Pembayaran Berhasil')
                    ->view('admin.email.pembayaran_berhasil');
    }
}
