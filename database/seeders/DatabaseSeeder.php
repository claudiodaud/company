<?php

namespace Database\Seeders;


use App\Models\Company;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        //Company::factory(3)->create();
        User::create([
                'name' => 'claudio daud',
            'email' => 'claudio.daud1@gmail.com',
            'email_verified_at' => now(),
            'password' => bcrypt('password'), // password
            'remember_token' => Str::random(10),

        ]);

        
        
    }
}
