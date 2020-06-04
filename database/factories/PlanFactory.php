<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Laravue\Models\Plan;
use Faker\Generator as Faker;

$factory->define(Plan::class, function (Faker $faker) {
    return [
		'name' => $faker->jobTitle,
        'description' => $faker->sentence($nbWords = 3, $variableNbWords = true),
        'slug' => $faker->slug,
        'price' => $faker->numberBetween($min = 100, $max = 300),
        'quantity_days' => rand(30,180),
        'status' => 'PUBLISHED',
        'user_id' => rand(1,30),
        'user_ip_created' => $faker->ipv4
    ];
});
