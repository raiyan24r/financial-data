<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FormSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $subject, $startDate, $endDate;
    public function __construct($companyName, $startDate, $endDate)
    {
        //
        $this->subject = $companyName;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('raiyan24r@gmail.com', 'Raiyan Ibne Hafiz')
            ->subject($this->subject)
            ->view('emails.form-submitted');
    }
}
