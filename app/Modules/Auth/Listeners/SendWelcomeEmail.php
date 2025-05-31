<?php

namespace App\Modules\Auth\Listeners;

use App\Modules\Auth\Events\UserCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendWelcomeEmail
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function handle(UserCreated $event)
    {
        $user = $event->new_user;
        $pass = $event->pass;
        $link = env('APP_URL_FRONT').'/login';
		$mail = sampleMail();
		$mail->user->email = $user->email;
		$mail->title = "¡Bienvenido/a a ".env('APP_NAME')."!";
		$mail->subject = "¡Bienvenido/a a ".env('APP_NAME')."!";
		$mail->body = "Hola, <strong>" .$user->name . " " . $user->surnames."</strong><br>Estamos emocionados de darte la bienvenida a <strong>".env('APP_NAME')."</strong>. Su contraseña para acceder al sistema es: <strong>".$pass."</strong><br>Por favor, haga clic en el siguiente botón para iniciar sesión.";
		$mail->link = $link;
		$mail->textBtn = "Ir  a ".env('APP_NAME')."";
		$mail->sub_body =  "¡Muchos Éxitos! El equipo de <strong>".env('APP_NAME')."</strong>";

        mailSend($mail);

    }
}
