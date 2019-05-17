<?php

namespace App\Http\Controllers\Api;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Agenda;
use App\API\APIError;
Use App\API\Funcoes;

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
        //$data = Funcoes::ValidaFinaisDeSemana('2019-05-16');
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

            if (is_null($dataInicial) || empty($dataInicial) || $dataInicial == "0000-00-00"
                || is_null($dataPrazo) || empty($dataPrazo) || $dataPrazo == "0000-00-00"
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

            //Valida Finais de semana
            if( Agenda::ValidaFinaisDeSemana($dataInicial) || Agenda::ValidaFinaisDeSemana($dataPrazo) || Agenda::ValidaFinaisDeSemana($dataConclusao)){
                $data = ['data' => 'As datas não podem ser em finais de semana.'];
                return response()->json($data);
            }

            //Validação para não sobrepor compromissos de um mesmo responsavel
            if(Agenda::VerificaDataAgendaUp($dataInicial, $dataPrazo, $responsavel, $this->agenda)){
                $data = ['data' => 'Não pode cadastrar na mesma data ou que se sobreponham à outras datas de atividades de um mesmo responsavel'];
                return response()->json($data);
            }

            $agendaData = $request->all();
            $this->agenda->create($agendaData);

            $return = ['data' => ['mg' => 'Tarefa cadastrada com sucesso']];
            return response()->json($return, 201);
        }
        catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(APIError::MensagemErro($e->getMessage(), 1000), 500);
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1000), 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $agenda = $this->agenda->find($id);

        if(!$agenda){
            $return = ['data' => ['mg' => 'Produto não encontrado']];
            return response()->json($return, 404);
        }
        $data = ['data' => $agenda];
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
            $busca = $this->agenda->find($id);

            $dataInicial = $request->get('data_inicio');
            //$dataPrazo = $request->get('data_prazo');
            $dataConclusao = $request->get('data_conclusao');
            if($busca->data_inicio != $dataInicial && !is_null($dataInicial)){
                $data = ['data' => 'Data inicial não pode ser alterada'];
                return response()->json($data);
            }

            //Verifica se recebeu novo dado
            if($request->get('data_prazo')){
                $dataPrazo = $request->get('data_prazo');
            }//Se não, seta variavel do banco
            else{
                $dataPrazo = $busca->data_prazo;
            }

            //Verifica se recebeu novo dado
            if($request->get('responsavel')){
                $responsavel = $request->get('responsavel');
            }//Se não, seta variavel do banco
            else{
                $responsavel = $busca->responsavel;
            }

            if ($dataInicial > $dataPrazo) {
                $data = ['data' => 'Data inicial maior que a final'];
                return response()->json($data);
            }
            if ($dataConclusao != "0000-00-00" && !is_null($dataConclusao) && $dataInicial > $dataConclusao) {
                $data = ['data' => 'Data inicial maior que a de conclusão'];
                return response()->json($data);
            }

            //Valida FInais de semana
            if( Agenda::ValidaFinaisDeSemana($dataInicial) || Agenda::ValidaFinaisDeSemana($dataPrazo) || Agenda::ValidaFinaisDeSemana($dataConclusao)){
                $data = ['data' => 'As datas não podem ser em finais de semana.'];
                return response()->json($data);
            }
            //Validação para não sobrepor compromissos de um mesmo responsavel
            if(Agenda::VerificaDataAgendaUp($dataInicial, $dataPrazo, $responsavel, $this->agenda)){
                $data = ['data' => 'Não pode cadastrar na mesma data ou que se sobreponham à outras datas de atividades de um mesmo responsavel'];
                return response()->json($data);
            }
            $agendaData = $request->all();
            $agenda =  $this->agenda->find($id);
            $agenda->update($agendaData);

            $return = ['data' => ['mg' => 'Tarefa atualizada com sucesso']];
            return response()->json($return, 201);
        }
        catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(APIError::MensagemErro($e->getMessage(), 1001), 500);
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1001), 500);
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
                return response()->json(APIError::MensagemErro($e->getMessage(), 1002), 500);
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1002), 500);
        }
    }
    public function search($data_inicial, $data_final){
        $data = $this->agenda->where('data_inicio','>=',$data_inicial)->where('data_inicio','<=',$data_final)->get();
        $data = ['data' => $data];
        return response()->json($data);
    }
}
