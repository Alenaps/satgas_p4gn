<?php

namespace App\Mail;

use App\Models\LaporanModel;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LaporanTerkirimMail extends Mailable
{
    use Queueable, SerializesModels;

    public LaporanModel $laporan;

    public function __construct(LaporanModel $laporan)
    {
        $this->laporan = $laporan;
    }

    public function build()
    {
        return $this->subject('Kode & Token Laporan Anda')
            ->view('emails.laporan_terkirim');
    }
}