<?php

use Faker\Generator as Faker;
use App\Domain\Team\Team;

$factory->define(Team::class, function (Faker $faker) {
    return [
        'title' => $faker->word
    ];
});
