<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Dispatcher;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        Dispatcher::updateOrCreate([
            'registered_number' => preg_replace('/\D/', '', env('CREDENCIAIS_CNPJ')),
        ], [
            'address_zipcode' => preg_replace('/\D/', '', env('ENVIO_CEP')),
            'registered_number' => preg_replace('/\D/', '', env('CREDENCIAIS_CNPJ')),
        ]);
    }
}
