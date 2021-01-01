<?php

namespace App\Mail;

use App\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PaymentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $payment;

    /**
     * Create a new message instance.
     *
     * @param Payment $payment
     * @return void
     */
    public function __construct(Payment $payment)
    {
        $this->payment = $payment;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $pdf = \PDF::loadView('payment.pdf', ['payment' => $this->payment])->setPaper('letter');

        return $this->markdown('email.payment')->attachData($pdf->output(), 'payment.pdf');
    }
}
