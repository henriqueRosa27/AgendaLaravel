<?php


namespace App\Repositories;

use App\Agenda;
use Carbon\Carbon;
use App\Repositories\AgendaRepositoryInterface;

class AgendaRepository implements AgendaRepositoryInterface
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
    public function ValidaFinaisDeSemana($data){
        if($data == NULL){
            return false;
        }
        $locale = 'pt_BR';
        Carbon::setLocale($locale);
        $data = Carbon::parse($data);
        if($data->dayOfWeek == 6 || $data->dayOfWeek == 0){
            return true;
        }
        return false;
    }

    public function VerificaDataAgendaUp($dataInicial, $dataFinal, $responsavel){

        $agenda = new Agenda;
        return $agenda->where('responsavel','=', $responsavel)->where(function($dado) use ($dataInicial,$dataFinal){
                                                                    $dado->whereBetween('data_inicio', [$dataInicial,$dataFinal])
                                                                        ->orWhereBetween('data_prazo', [$dataInicial,$dataFinal]);
        })->exists();

    }
}