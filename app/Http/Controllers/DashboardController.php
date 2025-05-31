<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Document;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class DashboardController extends Controller
{
    function index()
    {
        $user = Auth::user();
        $id = $user->id;
        $total = 0;
        $porcentaje = 0.27;
        $ahorro = $total * $porcentaje;

        $mesActual = Carbon::now()->month; // Obtiene el número del mes actual
        $conteo = 0;

        $last = [];

        return response([
            "ahorro" => $ahorro,
            "conteo" => $conteo,
            "last" =>   $last
        ], 200);
    }
}
