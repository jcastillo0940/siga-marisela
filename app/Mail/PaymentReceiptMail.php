<?php

namespace App\Mail;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class PaymentReceiptMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Payment $payment,
        public $pdfContent
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Recibo de Pago - ' . $this->payment->payment_code,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.payment-receipt',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(fn () => $this->pdfContent, 'recibo-' . $this->payment->payment_code . '.pdf')
                ->withMime('application/pdf'),
        ];
    }
}