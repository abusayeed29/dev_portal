<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionEmailNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $user_id = "";
    public $description = "";
    public $requisition_id = "";

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user_id, $description, $requisition_id)
    {
        $this->description = $description;
        $this->user_id = $user_id;
        $this->requisition_id = $requisition_id;
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
        $description        = $this->description;
        $notify_user_id     = $this->user_id;
        $requisition_id     = $this->requisition_id;
        return (new MailMessage)
                ->subject('Requsition# '.$requisition_id)
                ->from('nhd@navana.com', 'Vhehicle Requisition')
                ->view('emails.requisitionmail', compact('description','notify_user_id','requisition_id'));
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
