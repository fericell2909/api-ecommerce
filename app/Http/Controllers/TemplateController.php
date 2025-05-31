<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TemplateController extends Controller
{
    function recoveryPassword(){
        $datos = sampleMail();
        $datos->title = "Recupera tu contraseña";
        $datos->body = "¿Olvidaste tu contraseña? ¡No te preocupes! Haz click en el botón verde
        para restablecer la contraseña. Si no ves el botón, haz click aquí.";
        $datos->link = env('APP_URL_FRONT').'/forgot-password';
        $datos->textBtn = "Restablecer contraseña";
        return response(view('mails.base2',['data'=>$datos]),200);
    }
}