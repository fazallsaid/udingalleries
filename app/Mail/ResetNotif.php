<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Users; // Asumsikan model User kamu ada di sini

class ResetNotif extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Properti publik ini akan otomatis tersedia di file Blade
     */
    public $user;
    public $token;
    public $resetUrl;

    /**
     * Buat instance pesan baru.
     * Kita terima data dari controller di sini.
     */
    public function __construct(Users $user, string $token, string $resetUrl)
    {
        $this->user = $user;
        $this->token = $token;
        $this->resetUrl = $resetUrl;
    }

    /**
     * Atur subject email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            // Kita ambil subject dari controller atau bisa hardcode di sini
            subject: 'Reset Password - Udin Gallery',
        );
    }

    /**
     * Atur template/view email.
     */
    public function content(): Content
    {
        return new Content(
            // Nama file view blade
            view: 'emails.reset-password',

            // Kita tidak perlu 'with' lagi,
            // karena properti public (user, token, resetUrl)
            // sudah otomatis dikirim ke view.
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
