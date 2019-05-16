<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agenda;
use App\API\APIError;

class AgendaController extends Controller
{
    /**
     * @var Agenda
     */
    private $agenda;

    public function __construct(Agenda $agenda)
    {
        $this->agenda = $agenda;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ['data' => $this->agenda->all()];

        return response()->json($data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $dataInicial = $request->get('data_inicio');
            $dataPrazo = $request->get('data_prazo');
            $status = $request->get('status');
            $titulo = $request->get('titulo');
            $responsavel = $request->get('responsavel');

            //Verifica Variavel data_conclusao recebida
            if (is_null($request->get('data_conclusao'))) {
                $dataConclusao = NULL;
            } else {
                $dataConclusao = $request->get('data_conclusao');
            }
            //Verifica Variavel descricao recebida
            if (is_null($request->get('descricao'))) {
                $descricao = NULL;
            } else {
                $descricao = $request->get('descricao');
            }

            if (is_null($dataInicial) || empty($dataInicial) || $dataInicial != "0000-00-00"
                || is_null($dataPrazo) || empty($dataPrazo) || $dataPrazo != "0000-00-00"
                || is_null($status) || empty($status)
                || is_null($titulo) || empty($titulo)
                || is_null($responsavel) || empty($responsavel)
            ) {
                $data = ['data' => 'Valores obrigatórios passados como NULL, vazios ou incorretos. Favor conferir valores informados.'];
                return response()->json($data);
            }

            if ($dataInicial > $dataPrazo) {
                $data = ['data' => 'Data inicial maior que a final'];
                return response()->json($data);
            }
            if ($dataConclusao != "0000-00-00" && !is_null($dataConclusao) && $dataInicial > $dataConclusao) {
                $data = ['data' => 'Data inicial maior que a de conclusão'];
                return response()->json($data);
            }
            //$data = ['data' => 'OK'];
            //return response()->json($data);
            $agendaData = $request->all();
            $this->agenda->create($agendaData);

            $return = ['data' => ['mg' => 'Tarefa cadastrada com sucesso']];
            return response()->json($return, 201);
        }
        catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(APIError::MensagemErro($e->getMessage(), 1000));
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1000));
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $id)
    {
        $data = ['data' => $id];
        return response()->json($data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $dataInicial = $request->get('data_inicio');
            $dataPrazo = $request->get('data_prazo');
            $status = $request->get('status');
            $titulo = $request->get('titulo');
            $responsavel = $request->get('responsavel');

            //Verifica Variavel data_conclusao recebida
            if (is_null($request->get('data_conclusao'))) {
                $dataConclusao = NULL;
            } else {
                $dataConclusao = $request->get('data_conclusao');
            }
            //Verifica Variavel descricao recebida
            if (is_null($request->get('descricao'))) {
                $descricao = NULL;
            } else {
                $descricao = $request->get('descricao');
            }

            if ($dataInicial > $dataPrazo) {
                $data = ['data' => 'Data inicial maior que a final'];
                return response()->json($data);
            }
            if ($dataConclusao != "0000-00-00" && !is_null($dataConclusao) && $dataInicial > $dataConclusao) {
                $data = ['data' => 'Data inicial maior que a de conclusão'];
                return response()->json($data);
            }
            //$data = ['data' => 'OK'];
            //return response()->json($data);
            $agendaData = $request->all();
            $agenda =  $this->agenda->find($id);
            $agenda->update($agendaData);

            $return = ['data' => ['mg' => 'Tarefa atualizada com sucesso']];
            return response()->json($return, 201);
        }
        catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(APIError::MensagemErro($e->getMessage(), 1001));
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1001));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Agenda $id)
    {
        try{
            $id->delete();
            $return = ['data' => ['mg' => 'Tarefa excluida com sucesso']];
            return response()->json($return, 201);
        }
        catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(APIError::MensagemErro($e->getMessage(), 1002));
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1002));
        }
    }
}
