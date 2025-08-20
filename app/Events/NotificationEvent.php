<?php

namespace App\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationEvent
{
    use Dispatchable, SerializesModels;

    public $user;
    public $title;
    public $message;
    public $url;

    public function __construct($user, $title, $message, $url)
    {
        $this->user = $user;  // Bisa null untuk semua user
        $this->title = $title;
        $this->message = $message;
        $this->url = $url;
    }
}
