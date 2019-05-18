<?php

namespace App;
//require_once 'vendor/autoload.php';

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'id',
        'data_inicio',
        'data_prazo',
        'data_conclusao',
        'status',
        'titulo',
        'descricao',
        'responsavel',

    ];
    //protected $hidden = ['id'];

    protected $table = "Agenda";
}
