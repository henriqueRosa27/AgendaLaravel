<?php


namespace App\Repositories;

use App\Agenda;
use Carbon\Carbon;

class AgendaRepository
{
    protected $agenda;

    public function __construct(Agenda $agenda)
    {
        $this->agenda = $agenda;
    }

    public function all()
    {
        return $this->agenda->all();
    }

    public function find($id)
    {
        return $this->agenda->find($id);
    }

    public function findBy($coluna, $parametro)
    {
        return $this->agenda->where($coluna, $parametro)->get();
    }

    public function update($agenda){
        $this->agenda->update($agenda);
    }

    public function create($agenda){
        $this->agenda->create($agenda);
    }

    public function delete($id){
        $agenda = $this->agenda->find($id);
        $agenda->delete();
    }

    public function search($data_inicial, $data_final){
        return $this->agenda->where('data_inicio','>=',$data_inicial)->where('data_inicio','<=',$data_final)->get();
    }


    //Regras de negócio;
    public static function ValidaFinaisDeSemana($data){
        $data = Carbon::parse($data);
        if($data->dayOfWeek == 6 || $data->dayOfWeek == 0){
            return true;
        }
        return false;
    }

    public static function VerificaDataAgendaUp($dataInicial, $dataFinal, $responsavel){

        $agenda = new Agenda;
        return $agenda->where('responsavel','=', $responsavel)->where(function($dado) use ($dataInicial,$dataFinal){
                                                                $dado->whereBetween('data_inicio', [$dataInicial,$dataFinal])
                                                                    ->orWhereBetween('data_prazo', [$dataInicial,$dataFinal]);
        })->exists();

    }

    public $regraValidacao = [
        'data_inicio'       => 'required|date_format:Y-m-d',
        'data_prazo'        => 'required|date_format:Y-m-d|after:data_inicio',
        'data_conclusao'    => 'date_format:Y-m-d|after:data_inicio',
        'status'            => 'required',
        'titulo'            => 'required',
        'responsavel'       => 'required',
    ];
    public $mensagemValidacao = [
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