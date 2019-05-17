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

    public $regaValidacao = [
        'data_inicio'       => 'required|date_format:Y-m-d',
        'data_prazo'        => 'required|date_format:Y-m-d|after:data_inicio',
        'data_conclusao'    => 'date_format:Y-m-d|after:data_inicio',
        'status'            => 'required',
        'titulo'            => 'required',
        'responsavel'       => 'required',
    ];
    public $mensagensValidacao = [
        'data_inicio.required'          => 'Campo data_inicio é um campo obrigatório.',
        'data_prazo.required'           => 'Campo data_prazo é um campo obrigatório.',
        'status.required'               => 'Campo status é um campo obrigatório.',
        'titulo.required'               => 'Campo titulo é um campo obrigatório.',
        'responsavel.required'          => 'Campo responsavel é um campo obrigatório.',
        'data_inicio.date_format'       => 'Campo data_inicio deve ter o formato(Y-m-d).',
        'data_prazo.date_format'        => 'Campo data_prazo deve ter o formato(Y-m-d).',
        'data_conclusao.date_format'    => 'Campo data_conclusao deve ter o formato(Y-m-d).',
        'data_prazo.after'              => 'Campo data_prazo deve ser maior que data_inicio.',
        'data_conclusao.after'          => 'Campo data_conclusao deve ser maior que data_inicio.'
    ];
}
