<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmAccountMail extends Mailable
{
    use Queueable, SerializesModels;

    public $target;
    public $subject;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(string $target, string $subject, array $data)
    {
        $this->target = $target;
        $this->subject = $subject;
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->to($this->target)
            ->subject($this->subject)
            ->view('confirm_account');
    }
}
