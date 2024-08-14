<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotifyMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fullname;
    public $product_name;
    public $remaining_quantity;
    public $unit;
    public $coverage_days;

    /**
     * Create a new message instance.
     */
    public function __construct($fullname, $product_name, $remaining_quantity, $unit, $coverage_days)
    {
        $this->fullname = $fullname;
        $this->product_name = $product_name;
        $this->remaining_quantity = $remaining_quantity;
        $this->unit = $unit;
        $this->coverage_days = $coverage_days;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->view('emails.notify')
                    ->subject('Stock Alert')
                    ->with([
                        'fullname' => $this->fullname,
                        'product_name' => $this->product_name,
                        'remaining_quantity' => $this->remaining_quantity,
                        'unit' => $this->unit,
                        'coverage_days' => $this->coverage_days,
                        'url' => url('/'), // You may change this to the relevant URL
                    ]);
    }
}
