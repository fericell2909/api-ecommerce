<?php

function sampleMail()
{
	return (object)[
		'type_template' => '',
		'title' => 'Titulo',
		'sub_title' => '',
		'subject' => '[' . env('APP_NAME') . '] mail de ejemplo!',
		'body' => 'Lorem Ipsum es simplemente el texto de relleno de las imprentas y archivos de texto. Lorem Ipsum ha sido el texto de relleno estándar de las industrias desde el año 1500, cuando un impresor (N. del T. persona que se dedica a la imprenta) desconocido usó una galería de textos y los mezcló de tal manera que logró hacer un libro de textos especimen.',
		'link' => false,
		'textBtn' => 'Link para detalle',
		'sub_body' => false,
		'user' => (object)[
			"email" => "murdaneta@gmail.com",
			"name" => "",
		],
	];
}

function mailSend($body, $copia = '')
{
	$template = 'mails.base2';
	$mail = ($body->user->email ?? 'sin correo');
	$type_mail = ($body->title ?? 'sin asunto') . " | " . ($body->sub_title ?? 'sin titulo');
	$details = "Correo destino: " . $mail . "\nTipo de correo: " . $type_mail;

    if(env('APP_ENV') !== 'production'){

        $body->subject = ' [ PRUEBA ] ' . $body->subject;

    }

	try {
		Illuminate\Support\Facades\Mail::to($body->user->email)->send(
			new App\Mail\Mail(
				['data' => $body],
				$copia,
				$template,
				$body->subject
			)
		);
	} catch (Exception $e) {
		$message = $e->getMessage();
		Illuminate\Support\Facades\Log::info('ERROR::EMAIL');
		Illuminate\Support\Facades\Log::info('Linea :' . $e->getLine());
		Illuminate\Support\Facades\Log::info('Error :' . $message);
		Illuminate\Support\Facades\Log::info('EMAIL :' . $details);
		Illuminate\Support\Facades\Log::info('Archivo :' . $e->getFile());
		return false;
	}
	return true;
}

function sendDiscord($msg = "sin mensaje")
{
	try {
		$webhook_url = env("URL_WEBHOOK_DISCORD");
		$message = [
			'content' => $msg,
		];
		$response = Illuminate\Support\Facades\Http::post($webhook_url, $message);
		Illuminate\Support\Facades\Log::info('STATUS::DISCORD' . json_encode($response->json()));
	} catch (Exception $e) {
		$message = $e->getMessage();
		Illuminate\Support\Facades\Log::info('ERROR::DISCORD' . $message);
	}
}

function filterOptionsArray(array $options, array $config)
{
	$out = [];
	foreach ($config as $key => $conf) {
		if (is_array($conf)) {
			list($type, $defaultValue) = $conf;
		} else {
			$type = $conf;
			$defaultValue = null;
		}

		if (isset($options[$key])) {
			$value = $options[$key];
			if (is_string($value)) {
				$value = trim($value);
			}
			if ($value === '' || $value === null) {
				$out[$key] = $defaultValue;
			} else {
				try {
					switch ($type) {
						case 'boolean':
							$out[$key] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
							break;
						case 'int':
							$out[$key] = intval($value);
							break;
						case 'number':
							$out[$key] = floatval($value);
							break;
						case 'object':
						case 'array':
							$out[$key] = is_string($value) ? json_decode($value) : $value;
							break;
						case 'string':
						default:
							$out[$key] = $value;
							break;
					}
				} catch (Exception $e) {
					$out[$key] = $defaultValue;
				}
			}
		} else {
			$out[$key] = $defaultValue;
		}
	}

	return $out;
}

function MessageEntorno($message){
    if(env('APP_ENV') == 'production'){
        return $message;
    }

    return " [ PRUEBA ] " . $message;

}
