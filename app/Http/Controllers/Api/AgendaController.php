<?php

namespace App\Http\Controllers\Api;

use App\Agenda;
use App\Tranformers\TransformAgenda;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
//use App\Agenda; //Remover após implementear todo Repository
use App\API\APIError;
use App\Repositories\AgendaRepositoryInterface;
//use League\Fractal\Manager;
//use League\Fractal\Resource\Collection;

class AgendaController extends Controller
{
    /**
     * @var Agenda
     */
    private $agenda;
    //private $fractal;

    public function __construct(AgendaRepositoryInterface $agenda/*, Manager $fractal*/)
    {
        $this->agenda = $agenda;
        //$this->fractal = $fractal;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $dados = $this->agenda->all();
        $fractal = fractal($dados, new TransformAgenda());
        return response()->json($fractal, 200);
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


            $valida = validator($request->all(), Agenda::getRegraValidacao(), Agenda::getMensagemValidacao());
            if($valida->fails()){
                $data = ['data' => $valida->messages()];
                return response()->json($data, 200);
            }

            //Preferi não instanciar a classe, pois só irei usar alguns elementos
            $dataInicial = $request->get('data_inicio');
            $dataPrazo = $request->get('data_prazo');
            $responsavel = $request->get('responsavel');

            //Verifica Variavel data_conclusao recebida
            if (is_null($request->get('data_conclusao'))) {
                $dataConclusao = NULL;
            }
            else {
                $dataConclusao = $request->get('data_conclusao');
            }

            //Verifica Variavel descricao recebida
            if (is_null($request->get('descricao'))) {
                $descricao = NULL;
            }
            else {
                $descricao = $request->get('descricao');
            }

            //Valida Finais de semana
            if( $this->agenda->ValidaFinaisDeSemana($dataInicial) || $this->agenda->ValidaFinaisDeSemana($dataPrazo) || $this->agenda->ValidaFinaisDeSemana($dataConclusao)){
                $data = ['data' => 'As datas não podem ser em finais de semana.'];
                return response()->json($data, 500);
            }

            //Validação para não sobrepor compromissos de um mesmo responsavel
            if($this->agenda->VerificaDataAgendaUp($dataInicial, $dataPrazo, $responsavel)){
                $data = ['data' => 'Não pode cadastrar na mesma data ou que se sobreponham à outras datas de atividades de um mesmo responsavel'];
                return response()->json($data, 500);
            }

            $agendaData = $request->all();
            $this->agenda->create($agendaData);

            $return = ['data' => ['msg' => 'Tarefa cadastrada com sucesso']];
            return response()->json($return, 200);
        }
        catch(Exception $e){
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
        //$agenda = $this->agenda->findBy('id',$id);
        $agenda = $this->agenda->find($id);

        if(!$agenda){
            $return = ['data' => ['mg' => 'Tarefa não encontrada']];
            return response()->json($return, 404);
        }
        /*$data = new Collection($this->agenda->all(), new TransformAgenda());
        return $this->fractal->createData($data)->toJson();*/
        $data = ['data' => $agenda];
        $fractal = fractal($data, new TransformAgenda());
        return response()->json($fractal, 200);
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
            /*
             * Essa validação foi retirada, pois o cliente pode preferir enviar somente um dado do formulario
             * para alteração, e essa validação iria validar todos os dados
             */
            /*
            $valida = validator($request->all(), $this->agenda->$regraValidacao, $this->agenda->mensagensValidacao);
            if($valida->fails()){
                //return $valida->errors();
                $data = ['data' => $valida->messages()];
                return response()->json($data);
            }*/

            $busca = $this->agenda->find($id);

            $dataInicial = $request->get('data_inicio');
            //$dataPrazo = $request->get('data_prazo');
            $dataConclusao = $request->get('data_conclusao');

            /*
             * Pela lógica, depois de criado uma tarefa, a data inicial não poderá ser alterada
             *sendo assim está regra impede isso
             */
            if($busca->data_inicio != $dataInicial && !is_null($dataInicial)){
                $data = ['data' => 'Data inicial não pode ser alterada'];
                return response()->json($data, 500);
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

            //Valida Finais de semana
            //Retirada data_inicial, pois a validação já foi feita no insert, e não pode mais alterar a data a partir disso
            if( $this->agenda->ValidaFinaisDeSemana($dataPrazo) || $this->agenda->ValidaFinaisDeSemana($dataConclusao) || $this->agenda->ValidaFinaisDeSemana($dataConclusao)){
                $data = ['data' => 'As datas não podem ser em finais de semana.'];
                return response()->json($data);
            }
            //Validação para não sobrepor compromissos de um mesmo responsavel em relação as datas
            if($this->agenda->VerificaDataAgendaUp($dataInicial, $dataPrazo, $responsavel, $this->agenda)){
                $data = ['data' => 'Não pode cadastrar na mesma data ou que se sobreponham à outras datas de atividades de um mesmo responsavel'];
                return response()->json($data, 500);
            }

            $agendaData = $request->all();
            $this->agenda =  $this->agenda->find($id);
            $this->agenda->update($agendaData);

            $return = ['data' => ['mg' => 'Tarefa atualizada com sucesso']];
            return response()->json($return, 200);
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
    public function delete($id)
    {
        try{
            $agenda = $this->agenda->find($id);
            if(!$agenda){
                $return = ['data' => ['mg' => 'Tarefa não encontrada']];
                return response()->json($return, 404);
            }
            $this->agenda->delete($id);
            $return = ['data' => ['mg' => 'Tarefa excluida com sucesso']];
            return response()->json($return, 200);
        }
        catch(\Exception $e){
            if(config('app.debug')){
                return response()->json(APIError::MensagemErro($e->getMessage(), 1002), 500);
            }
            return response()->json(APIError::MensagemErro('Ocorreu um erro ao realizar operação', 1002), 500);
        }
    }
    public function search($data_inicial, $data_final){
        $data = $this->agenda->search($data_inicial,$data_final);
        $data = ['data' => $data];
        return response()->json($data);
    }
}
