<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'data_inicio',
        'data_prazo',
        'data_conclusao',
        'status',
        'titulo',
        'descricao',
        'responsavel'
    ];
    protected $hidden = ['agenda_id'];

    protected $table = "Agenda";

    protected function validate_datetime($attribute, $value)
    {
        // separa data e hora
        $pieces = explode(' ', $value);
        if ( count($pieces) == 2 ) {
            $date_piece = $pieces[0];
            $time_piece = $pieces[1];
            // lista os dados de data e hora
            list($day, $month, $year) = explode('/', $date_piece);
            list($hour, $minute) = explode(':', $time_piece);
            // data eh valida?
            $date_is_valid = checkdate($month, $day, $year);
            // hora eh valida?
            $time_is_valid = false;
            if ( ($hour > -1 && $hour < 24) && ($minute > -1 && $minute < 60) ) {
                $time_is_valid = true;
            }
            // data e hora sao validos?
            if ( $date_is_valid && $time_is_valid ) return true;
        }
        return false;
    }

    //Regras de negócio;
}
