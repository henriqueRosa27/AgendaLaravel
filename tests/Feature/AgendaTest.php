<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AgendaTest extends TestCase
{
    public function testAoListar()
    {
        //Status esperado 200, por haver registros na base de dados
        $response = $this->get('/api/agenda');
        $response->assertStatus(200);
    }

    public function testAoListarRegistroUnico()
    {
        //Status esperado 200, por haver registro com id = 1 na base de dados
        $response = $this->get('/api/agenda/1');
        $response->assertStatus(200);

        //Status esperado 404, por não haver registro com id = 100 na base de dados
        $response = $this->get('/api/agenda/100');
        $response->assertStatus(404);
    }

    /*public function testAoInserir()
    {
        //Status esperado 500, por já haver registro para esse usuario na mesma data
        $dado = [
            'data_inicio'   =>'2019-07-24',
            'data_prazo'    =>'2019-07-25',
            'status'        =>'Baklog',
            'titulo'        =>'Tarefa 01',
            'responsavel'   =>'Teste'
        ];
        $this->json('post','/api/agenda', $dado)
                ->assertStatus(500);

        //Status esperado 200, por ser um resgistro para um responsavel que não foi cadastrado ainda
        $dado = [
            'data_inicio'   =>'2019-07-24',
            'data_prazo'    =>'2019-07-25',
            'status'        =>'Baklog',
            'titulo'        =>'Tarefa 01',
            'responsavel'   =>'Test'
        ];
        $this->json('post','/api/agenda', $dado)
            ->assertStatus(200);
    }*/

    public function testAoUpdate(){
        $dado = [
            'data_inicio'   =>'2019-07-24'
        ];
        $id = 1;

        //Status esperado 500, por não poder alterar data inicial
        $this->json('put','/api/agenda/'.$id, $dado)
            ->assertStatus(500);

        //Status esperado 200
        $dado = [
            'data_prazo'   =>'2019-07-24'
        ];
        $this->json('put','/api/agenda/1', $dado)
            ->assertStatus(200);

        $dado = [
            'data_prazo'   =>'2019-07-27'
        ];
        //Status esperado 500, devido a data nõa poder ser final de semana
        $this->json('put','/api/agenda/1', $dado)
            ->assertStatus(500);
    }

    public function testAoDelete(){
        $id = 26;
        //Status esperado 200
        $this->json('DELETE', '/api/agenda/' . $id)
            ->assertStatus(200);

        $id = 100;
        //Status esperado 200, por não haver registro com esse id
        $this->json('DELETE', '/api/agenda/' . $id)
            ->assertStatus(404);
    }
}
