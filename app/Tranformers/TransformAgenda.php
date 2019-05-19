<?php


namespace App\Tranformers;

use App\Agenda;
use League\Fractal\TransformerAbstract;

class TransformAgenda extends TransformerAbstract
{
    public function Transform (Agenda $agenda){
        return [
            'id'                => (int)$agenda->id,
            'data_inicio'       => $agenda->data_inicio,
            'data_prazo'        => $agenda->data_prazo,
            'data_conclusao'    => $agenda->data_conclusao,
            'status'            => (string)$agenda->status,
            'titulo'            => (string)$agenda->titulo,
            'descricao'         => (string)$agenda->descricao,
            'responsavel'       => (string)$agenda->responsavel
        ];
    }
}