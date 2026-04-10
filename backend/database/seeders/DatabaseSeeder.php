<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lote; // Importamos el modelo Lote
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Usaremos credenciales fáciles de recordar para el evaluador
        User::factory()->create([
            'name' => 'Admin Manuel',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'), // Siempre usa Hash para las contraseñas
        ]);

        // 2. Creamos unos Lotes de prueba para que la tabla no esté vacía
        Lote::create([
            'nombre' => 'Residencial Los Olivos',
            'direccion' => 'Av. Central 123, CDMX',
            'identificador' => 'LOTE-001'
        ]);

        Lote::create([
            'nombre' => 'Plaza Comercial Norte',
            'direccion' => 'Calle 5 de Mayo, Neza',
            'identificador' => 'LOTE-002'
        ]);

        Lote::create([
            'nombre' => 'Desarrollo Industrial Eco',
            'direccion' => 'Parque Industrial 4, Toluca',
            'identificador' => 'LOTE-003'
        ]);
        
        // Mensaje opcional en consola para saber que terminó
        $this->command->info('¡Base de datos sembrada con éxito! Usuario: admin@test.com / pass: password');
    }
}