<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatTableAgenda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agenda', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->date('data_inicio')->nullable(false);
            $table->date('data_prazo')->nullable(false);
            $table->date('data_conclusao')->nullable(true);
            $table->string('status', 150)->nullable(false);
            $table->string('titulo', 200)->nullable(false);
            $table->string('descricao', 500)->nullable(true);
            $table->string('responsavel', 50)->nullable(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agenda');
    }
}
