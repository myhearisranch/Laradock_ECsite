<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Buy extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Buy',
        );
    }

    /**
     * Get the message content definition.
     */

     //この部分でメール内容を出力する
     //Laravel 9+ では、content メソッドが推奨され、ビューやデータの設定が Content クラスを使って行います。
     //Laravel 8 では、build メソッドを使ってメールの内容やビューを指定します。
    public function content(): Content
    {
        return new Content(
            view: 'buy.mail',
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
