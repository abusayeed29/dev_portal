<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TicketEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $description = "";
    public $tkt_owner = "";
    public $ticket_id = "";
    public $user_id = "";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($description, $tkt_owner, $ticket_id, $user_id)
    {
        //
        $this->description = $description;
        $this->tkt_owner = $tkt_owner;
        $this->ticket_id = $ticket_id;
        $this->user_id = $user_id;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        // return (new MailMessage)
        //             ->line('The introduction to the notification.')
        //             ->action('Notification Action', url('/'))
        //             ->line('Thank you for using our application!');
        $description = $this->description;
        $tkt_owner = $this->tkt_owner;
        $ticket_id = $this->ticket_id;
        $url = url('/');
        $t_url = url('/ticket/'. $ticket_id);
        $user_id = $this->user_id;
        
        return (new MailMessage)
                ->subject('Ticket# '.$ticket_id)
                ->from('nhd@navana.com', 'Navana Helpdesk')
                ->view('emails.ticketmail', compact('description','tkt_owner','url','user_id'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
