<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Journal::class, function (Faker $faker) {
    return [
        'issn'=> $faker->randomNumber($nbDigits = 6, $strict = true),
        'eissn' =>$faker->randomNumber($nbDigits = 6, $strict = true),
        'publisher' => $faker->colorName,
        'title'=> $faker->name,
        'total_articles'=> $faker->randomNumber($nbDigits  = 4)
    ];
});
