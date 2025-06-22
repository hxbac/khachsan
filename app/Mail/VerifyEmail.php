<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyEmail extends Mailable
{
    use SerializesModels;

    public $user;
    public $url;

    public function __construct($user, $url)
    {
        $this->user = $user;
        $this->url = $url;
    }

    public function build()
    {
        return $this->subject('Xác minh địa chỉ email')
                    ->view('emails.verify')
                    ->with([
                        'name' => $this->user->name,
                        'url'  => $this->url,
                    ]);
    }
}
