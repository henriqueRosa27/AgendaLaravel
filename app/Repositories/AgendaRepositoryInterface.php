<?php


namespace App\Repositories;

interface AgendaRepositoryInterface
{
    public function all();

    public function find($id);

    public function findBy($coluna, $parametro);

    public function update($agenda);

    public function create($agenda);

    public function delete($id);

    public function search($data_inicial, $data_final);


    //Regras de negócio;
    public function ValidaFinaisDeSemana($data);

    public function VerificaDataAgendaUp($dataInicial, $dataFinal, $responsavel);

}