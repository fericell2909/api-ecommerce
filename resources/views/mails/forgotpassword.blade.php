@extends('mails.basemails')
@section('content')
    <h2 style="text-align: center">Reestablecer su Contraseña</h2>
    <div style="padding: 10px;">
        Para Reestablecer su Contraseña, por favor complete el siguiente formulario:
        {{-- {{ URL::to('password/reset', [$wasabil->token]) }}. --}}
        {{ env('APP_URL_FRONT') . 'reset/password/' . $wasabil->token }}
    </div>
@endsection
