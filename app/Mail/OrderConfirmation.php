<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $order;
    public $cart;

    public function __construct(Order $order, array $cart)
    {
        $this->order = $order;
        $this->cart = $cart;
    }

    public function build()
    {
        return $this->subject('Order Confirmation')
                    ->view('emails.order_confirmation')
                    ->with([
                        'order' => $this->order,
                        'cart' => $this->cart,
                    ]);
    }
}