<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class EmailNotification extends Notification
{
    use Queueable;

    private $details;

    public function __construct($details)
    {
        $this->details = $details;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->details['subject'])
                    ->greeting("Bonjour " . $this->details['name'])
                    ->line($this->details['message'])
                    ->action('Voir plus', url('/'))
                    ->line('Merci de nous avoir contactés.');
    }
    
}
