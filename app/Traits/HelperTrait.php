<?php

namespace App\Traits;


trait HelperTrait
{
    /**
     * Generate message with environment.
     *
     * Returns the message if production or test
     *
     * @param string $message
     * @return string
     */

    //protected $page = Config::get('constants.current_page');
    //protected $records_per_page = Config::get('constants.records_per_page');

    public function __construct()
    {
    }
    public function MessageWithEnvironment($message = ""): string
    {
        if ($this->isProduction()) {
            return $message;
        }

        return "[" . __('settings.message.email') . "] " . $message;
    }

    public function isProduction()
    {
        if (env('APP_ENV') === 'production') {
            return true;
        }

        return false;
    }

    public function formatRUT($value)
    {

        $completeRut = str_replace(array('.', '-'), array('', ''), $value);

        // return array(
        //     substr($completeRut, 0, -1),
        //     substr($completeRut, -1),
        // );

        // $rutTmp = explode("-", $value);
        return number_format(substr($completeRut, 0, -1), 0, "", ".") . '-' . substr($completeRut, -1);
    }

    public function getrMonthName($numeroMes)
    {

        $nombresMeses = array(
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        );

        return $nombresMeses[intval($numeroMes)];
    }
}
