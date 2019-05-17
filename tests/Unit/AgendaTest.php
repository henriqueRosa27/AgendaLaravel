<?php

namespace Tests\Unit;

use App\Agenda;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;


class AgendaTest extends TestCase
{
    //$agenda = new Agenda;
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testExample()
    {
        $agenda = new Agenda;

        $this->assertEquals('2000-05-09', $agenda->data_inicio);
    }
}
