<?php

use Faker\Generator as Faker;

/* @var Illuminate\Database\Eloquent\Factory $factory */

$factory->define(App\Models\Gnome::class, function (Faker $faker) {
    return [
        'name' => $faker->firstName(),
        'strength' => $faker->numberBetween(0, 100),
        'age' => $faker->numberBetween(0, 100),
        'avatar_file' => $faker->image(public_path() . '/avatars', 200, 200, 'people', false),
    ];
});
