
<?php
    $footer_logo=null;
    $logo= null;

    $icon= asset('images/meditating.png');

    $ancho='60';
    $ancholaterales1='0';
    $ancholaterales='35';
    $color1='white';
    $color2='white';//1fa02e
    $color3="#673ab7";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <table width="700" style="margin: auto" cellpadding="0" cellspacing="0" border="0" bgcolor="#F1F5F8">
        <tr>
            <td style="background-color: {{$color1}};text-align:center; padding:10px 10px;" colspan="5">
                <img src="{{$logo}}" alt="" style="width: 150px">
            </td>
        </tr>

        <tr>
            <td style="background-color: {{$color2}};text-align:center;height:{{$ancho}}px;" height={{$ancho}} colspan="5">
            </td>
        </tr>

        <tr>
            <td style="background-color: {{$color2}};text-align:center;height:{{$ancholaterales1}}px;" width={{$ancholaterales1}}>
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancholaterales}}px;" width={{$ancholaterales}}>
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancho}}px;;padding:30px 0px 0px 0px;" height={{$ancho}}>
                <img src="{{$icon}}" alt="" style="width: 160px">
                <h2 style="margin:9px 0px; color:{{$color3}}">{!!$data->title!!}</h2>
                <h4 style="margin:9px 0px">{!!$data->sub_title!!}</h4>
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancholaterales}}px;" width={{$ancholaterales}}>
            </td>
            <td style="background-color: {{$color2}};text-align:center;height:{{$ancholaterales1}}px;" height={{$ancholaterales1}} width={{$ancholaterales1}}>
            </td>
        </tr>

        <tr>
            <td style="background-color: {{$color2}};text-align:center;height:{{$ancholaterales1}}px;" width={{$ancholaterales1}}>
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancholaterales}}px;" width={{$ancholaterales}}>
            </td>
            <td style="background-color: #fff;text-align:center;" height={{$ancho}}>
                {{-- <h3 style="background-color: #f1f3f8;padding:15px 0px 15px 0px;border-radius:5px;">
                    {!!$data->sub_title_2!!}
                </h3> --}}
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancholaterales}}px;" width={{$ancholaterales}}>
            </td>
            <td style="background-color: {{$color2}};text-align:center;height:{{$ancholaterales1}}px;" height={{$ancholaterales1}} width={{$ancholaterales1}}>
            </td>
        </tr>

        <tr>
            <td style="background-color: #f8f8f8;text-align:center;height:{{$ancholaterales1}}px;" width={{$ancholaterales1}}>
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancholaterales}}px;" width={{$ancholaterales}}>
            </td>
            <td style="background-color: #fff;text-align:center;" height={{$ancho}}>
                @if (isset($data->order) and $data->type_template!='')
                    @include('mails.'.$data->type_template, ['data' => $data])
                @endif
                <p style="text-align:left; padding: 15px 15px; font-size:16px; margin: auto;">
                    {!!$data->body!!}
                </p>
                <br>
                <br>
                <img src="{{$footer_logo}}" alt="" style="width: 150px">
                <br>
                <br>
                @if(isset($data->link)&&isset($data->textBtn) && $data->link != false)
                    <a
                        href="{!!$data->link!!}"
                        style="
                            font-size:16px;
                            text-decoration:none;
                            color:#fff;
                            font-weight:bold;
                            padding:10px 32px;
                            border:3px solid {{$color3}};
                            background:{{$color3}};
                            border-radius:5px;
                        "
                        target="_blank"
                    >
                        {{$data->textBtn}}
                    </a>
                @endif
                <br>
                <br>
                @if(isset($data->sub_body) && $data->sub_body != false)
                    {!!$data->sub_body!!}
                    <br/><br/>
                @endif
                <br>
            </td>
            <td style="background-color: #fff;text-align:center;height:{{$ancholaterales}}px;" width={{$ancholaterales}}>
            </td>
            <td style="background-color: #f8f8f8;text-align:center;height:{{$ancholaterales1}}px;" height={{$ancholaterales1}} width={{$ancholaterales1}}>
            </td>
        </tr>
        <tr>
            <td style="background-color: #f8f8f8;text-align:center;height:{{$ancho}}px;" height={{$ancho}} colspan="5">
                {{-- <br>
                <br>
                ¿Necesitas ayuda? <a href="{{env('APP_URL_FRONT')}}" target="_blank" style="color:{{$color3}};">Contáctanos</a>
                <br>
                <br> --}}
                © {{env('GET_NAME_APP')}}
                <br>
                Todos los derechos reservados {{date("Y")}}
                <br>
                Desarrollado por
		        <a style="color: {{$color3}};" href="https://www.gux.tech/" target="_blank">gux.tech</a>
                <br>
                <br>
            </td>
        </tr>
    </table>
</body>
</html>
