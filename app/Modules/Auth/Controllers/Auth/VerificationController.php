<?php

namespace App\Modules\Auth\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
	function verify(EmailVerificationRequest $request)
	{
		$request->fulfill();
		$mail = sampleMail();
		$mail->title = "¡Bienvenido/a a Wasabil!";
		$mail->subject = '¡Bienvenido/a a Wasabil!';
		$mail->body = "Estamos emocionados de darte la bienvenida a Wasabil. Ahora, declarar tus invoices de plataformas digitales será más fácil que nunca. Explora nuestras funcionalidades y si tienes alguna pregunta, no dudes en contactarnos.";
		$mail->sub_body = "¡Felices declaraciones! El equipo de Wasabil";
		$mail->user->email = $request->user()->email;
		mailSend(
			$mail
		);
		return redirect(env("APP_URL_FRONT") . '/?verification_mail=ok');
		return redirect('/home');
	}
}
