<?php

/* @var $factory \Illuminate\Database\Eloquent\Factory */

use App\Model;
use Faker\Generator as Faker;

$factory->define(\App\Output::class, function (Faker $faker) {
    return [
        'title'=> $faker->text,
        'doi' => '10.1186/s40323-016-0060-1',
        'publisher' => $faker->colorName,
        'issn'=> $faker->randomNumber($nbDigits = 6, $strict = true),
        'eissn'=> $faker->randomNumber($nbDigits = 6, $strict = true),
        'language' => $faker->languageCode,
        'reference_count'=> $faker->randomNumber($nbDigits  = 4),
    ];
});
