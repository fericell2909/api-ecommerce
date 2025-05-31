<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

class CustomVerifyEmail extends VerifyEmail
{
    use Queueable;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
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
        $url = $this->verificationUrl($notifiable);
        $data = sampleMail();
        $subject = '¡Confirma tu dirección de correo en Wasabil!';

        $data->title = $subject;
        
        $data->body = 'Hola, ' . $notifiable->name . ',<br/>';
        $data->body .= "¡Gracias por registrarte en Wasabil! Estamos encantados de tenerte con nosotros. ";
        $data->body .= "Por favor, confirma tu dirección de correo electrónico haciendo click en el siguiente enlace:";
        $data->link = $url;
        $data->textBtn = "Enlace de confirmación";
        $data->sub_body = "Una vez confirmado, podrás disfrutar de todas las ventajas que Wasabil tiene para ofrecerte.<br/>
        ¡Hasta pronto! El equipo de Wasabil";

        return (new MailMessage)
            ->subject($subject)
            ->view(
                'mails.base2',
                ['data' => $data]
            );
        // return (new MailMessage)
        //     ->line('The introduction to the notification.')
        //     ->action('Notification Action', url('/'))
        //     ->line('Thank you for using our application!');
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
