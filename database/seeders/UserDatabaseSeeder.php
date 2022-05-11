<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;

class UserDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'first_name'        => 'Cyril',
            'last_name'         => 'de Wit',
            'email'             => '453717@student.fontys.nl',
            'password'          => Hash::make('Welkom0!'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'first_name'        => 'Jasper',
            'last_name'         => 'Stolwijk',
            'email'             => 'jasper.stolwijk@student.fontys.nl',
            'password'          => Hash::make('Welkom0!'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'first_name'        => 'Mitch',
            'last_name'         => 'Kessels',
            'email'             => '453258@student.fontys.nl',
            'password'          => Hash::make('Welkom0!'),
            'email_verified_at' => Carbon::now(),
        ]);

        User::create([
            'first_name'        => 'Enno',
            'last_name'         => 'Overbeeken',
            'email'             => 'enno@kahuis.nl',
            'password'          => Hash::make('Welkom0!'),
            'email_verified_at' => Carbon::now(),
        ]);
    }
}
