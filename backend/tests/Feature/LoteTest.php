<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Lote;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoteTest extends TestCase
{
    use RefreshDatabase; // Esto limpia la base de datos de prueba en cada ejecución

    public function test_lista_de_lotes_es_accesible_para_usuarios_autenticados()
    {
        // 1. Creamos un usuario de prueba
        $user = User::factory()->create();

        // 2. Actuamos como ese usuario y pedimos los lotes
        $response = $this->actingAs($user)
                         ->getJson('/api/lotes');

        // 3. Verificamos que responda con éxito
        $response->assertStatus(200);
    }

    public function test_usuario_no_autenticado_no_puede_ver_lotes()
    {
        // Intentamos entrar sin loguearnos
        $response = $this->getJson('/api/lotes');

        // Debería responder con un error de "No autorizado"
        $response->assertStatus(401);
    }
}