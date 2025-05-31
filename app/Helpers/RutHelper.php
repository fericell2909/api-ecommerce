<?php

namespace App\Helpers;

use App\Models\Supplier;

class RutHelper
{

    public static function valida_rut($trut)
    {
        $trut = str_replace(array('.', '-'), array('', ''), $trut);
        $dvt = substr($trut, strlen($trut) - 1, strlen($trut));
        $rutt = substr($trut, 0, strlen($trut) - 1);
        $rut = ((int)($rutt) + 0);
        $pa = $rut;
        $c = 2;
        $sum = 0;
        while ($rut > 0) {
            $a1 = $rut % 10;
            $rut = floor($rut / 10);
            $sum = $sum + ($a1 * $c);
            $c = $c + 1;
            if ($c == 8) {
                $c = 2;
            }
        }
        $di = $sum % 11;
        $digi = 11 - $di;
        $digi1 = ((string)($digi));
        if (($digi1 == '10')) {
            $digi1 = 'K';
        }
        if (($digi1 == '11')) {
            $digi1 = '0';
        }
        if (($dvt == $digi1)) {
            return true;
        } else {
            return false;
        }
    }

    public  function canProcessRut($uuid, $rut): array
    {

        if ($this->valida_rut(str_replace(array('.', '-'), array('', ''), $rut)) === false) {
            return ['status' => false, 'message' => __('rut.valid')];
        }

        if ($rut === env('RUT_REPITER')) {
            return ['status' => true, 'message' => ''];
        }

        $supplier = Supplier::where('uid', $uuid)->first();
        $records = Supplier::where('rut', $rut)->get()->count();

        if ($supplier) {

            if ($records > 1) {
                return ['status' => false, 'message' => __('rut.exist')];
            }

            return ['status' => true, 'message' => ''];
        }

        if ($records  === 0) {
            return  ['status' => true, 'message' => ''];
        }

        return ['status' => false, 'message' =>  __('rut.exist')];
    }
}
