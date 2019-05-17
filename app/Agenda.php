<?php

namespace App;
//require_once 'vendor/autoload.php';

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
    public function VeridicaFinalDeSemana($dados){
        //date()
    }
}
