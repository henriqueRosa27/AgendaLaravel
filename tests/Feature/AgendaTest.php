<?php

namespace Tests\Feature;

use App\Agenda;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AgendaTest extends TestCase
{
    public function testAoListar()
    {
        $response = $this->get('/api/agenda');
        $response->assertStatus(200);
    }

    public function testAoInserir()
    {
        //Status esperado 500, por já haver registro para esse usuario na mesma data
        $dado = [
            'data_inicio'   =>'2019-07-24',
            'data_prazo'    =>'2019-07-25',
            'status'        =>'Baklog',
            'titulo'        =>'Tarefa 01',
            'responsavel'   =>'Teste'
        ];
        $this->json('post', 'api/agenda', $dado)
                ->assertStatus(500);

        //Status esperado 200, por ser um resgistro para um responsavel que não foi cadastrado ainda
        $dado = [
            'data_inicio'   =>'2019-07-24',
            'data_prazo'    =>'2019-07-25',
            'status'        =>'Baklog',
            'titulo'        =>'Tarefa 01',
            'responsavel'   =>'Test'
        ];
        $this->json('post', 'api/agenda', $dado)
            ->assertStatus(200);
    }
}
