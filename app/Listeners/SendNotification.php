<?php

namespace App\Listeners;

use App\Events\NotificationEvent;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendNotification
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(NotificationEvent $event): void
    {
        // Jika user null, notifikasi dikirim ke semua user
        if ($event->user) {
            Notification::create([
                'user' => $event->user,
                'title' => $event->title,
                'message' => $event->message,
                'url' => $event->url,
            ]);
        } else {
            $users = User::all();
            foreach ($users as $user) {
                Notification::create([
                    'user' => $user->id,
                    'title' => $event->title,
                    'message' => $event->message,
                    'url' => $event->url,
                ]);
            }
        }
    }
}
