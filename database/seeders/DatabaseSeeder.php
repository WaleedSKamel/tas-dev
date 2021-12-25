<?php

namespace Database\Seeders;

use App\Models\User;
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
        User::query()->create([
           'name' => 'admin',
            'email' => 'admin@example.com',
            'password' => '123456'
        ]);
        // \App\Models\User::factory(10)->create();
    }
}
