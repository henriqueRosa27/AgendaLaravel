<?php


namespace App\API;


use Carbon\Carbon;

class Funcoes
{
    public static function ValidaFinaisDeSemana($data){
        $dt = Carbon::parse($data);
        if($dt->dayOfWeek == 6 || $dt->dayOfWeek == 7){
            return true;
        }
        return false;
    }

    public static function VerificaData($data){
        return $validatedData = $data->validate([
            'data_inicio' => 'required|date_format:"Y-m-d"',
            'data_prazo' => 'required|date_format:"Y-m-d"|after:data_inicio',
            'data_conclusao' => 'date_format:"Y-m-d"|after:data_inicio'
        ]);
    }
}