<?php

namespace App;
//require_once 'vendor/autoload.php';

use Carbon\Carbon;
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

    //Regras de negócio;

    /**
     * @param $dados
     */
    public static function ValidaFinaisDeSemana($data){
        $dt = Carbon::parse($data);
        if($dt->dayOfWeek == 6 || $dt->dayOfWeek == 7){
            return true;
        }
        return false;
    }

    public static function VerificaDataAgendaUp($dataInicial, $dataFinal, $responsavel, $tabela){
        return $tabela->where('responsavel', $responsavel)->whereBetween('data_inicio', [$dataInicial,$dataFinal])
                ->orWhereBetween('data_prazo', [$dataInicial,$dataFinal])->exists();
    }

}
