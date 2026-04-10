<?php

namespace App\Services;

use App\Models\Lote;
use App\Models\Vendedor;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class LoteService
{
    public function getAll()
    {
        return Lote::withCount('vendedores')->get();
    }

    public function create($data)
    {
        return Lote::create($data);
    }

    public function find($id)
    {
        return Lote::with('vendedores')->findOrFail($id);
    }

    public function update($id, $data)
    {
        $lote = Lote::findOrFail($id);
        $lote->update($data);
        return $lote;
    }

    public function delete($id)
    {
        $lote = Lote::findOrFail($id);

        if ($lote->vendedores()->count() > 0) {
            throw new \Exception('No se puede eliminar: El lote tiene vendedores asignados.');
        }

        $lote->delete();
        return true;
    }

    public function importarVendedores($loteId, $cantidad = 3)
    {
        $lote = Lote::findOrFail($loteId);

        $usuarios = Cache::remember('api_vendedores_externos', 3600, function () {
            $response = Http::get('https://jsonplaceholder.typicode.com/users');
            return $response->successful() ? $response->json() : null;
        });

        if (!$usuarios) {
            throw new \Exception('Error al consumir la API externa');
        }

        $usuarios = collect($usuarios)->shuffle()->take($cantidad);

        foreach ($usuarios as $user) {
            Vendedor::updateOrCreate(
                ['email' => $user['email']],
                [
                    'nombre'         => $user['name'],
                    'username'       => $user['username'],
                    'telefono'       => $user['phone'],
                    'website'        => $user['website'],
                    'calle'          => $user['address']['street'],
                    'ciudad'         => $user['address']['city'],
                    'zipcode'        => $user['address']['zipcode'],
                    'latitud'        => $user['address']['geo']['lat'],
                    'longitud'       => $user['address']['geo']['lng'],
                    'empresa_nombre' => $user['company']['name'],
                    'lote_id'        => $lote->id
                ]
            );
        }

        return "Se importaron $cantidad vendedores correctamente al lote: {$lote->nombre}";
    }

    public function vendedoresPorLote($id)
    {
        return Vendedor::where('lote_id', $id)->get();
    }

    public function eliminarVendedor($id)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->delete();
    }

    public function actualizarVendedor($id, $data)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->update($data);
        return $vendedor;
    }
}