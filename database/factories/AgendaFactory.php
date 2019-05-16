<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */


use Faker\Generator as Faker;

$factory->define(App\Agenda::class, function (Faker $faker) {
    return [
        'data_inicio' => $faker->date(),
        'data_prazo' => $faker->date(),
        //'data_conclusao' => $faker->date(),
        'data_conclusao' => null,
        'status' => $faker->randomElement(['Backlog', 'Em Progesso', 'Finalizado']),
        'titulo' => $faker->name,
        'descricao' => $faker->text,
        'responsavel' => $faker->name
    ];
});
