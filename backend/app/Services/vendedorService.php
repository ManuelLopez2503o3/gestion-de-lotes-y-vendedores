<?php

namespace App\Services;

use App\Models\Vendedor;
use Illuminate\Support\Facades\Http;

class VendedorService
{
    public function getAll()
    {
        return Vendedor::all();
    }

    public function create($data)
    {
        return Vendedor::create($data);
    }

    public function find($id)
    {
        return Vendedor::findOrFail($id);
    }

    public function update($id, $data)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->update($data);
        return $vendedor;
    }

    public function delete($id)
    {
        $vendedor = Vendedor::findOrFail($id);
        $vendedor->delete();
    }

    public function importar($lote_id)
    {
        $response = Http::get('https://jsonplaceholder.typicode.com/users');
        $users = $response->json();

        foreach ($users as $user) {
            if (Vendedor::where('email', $user['email'])->exists()) {
                continue;
            }

            Vendedor::create([
                'nombre' => $user['name'],
                'username' => $user['username'],
                'email' => $user['email'],
                'telefono' => $user['phone'],
                'website' => $user['website'],
                'calle' => $user['address']['street'],
                'ciudad' => $user['address']['city'],
                'zipcode' => $user['address']['zipcode'],
                'latitud' => $user['address']['geo']['lat'],
                'longitud' => $user['address']['geo']['lng'],
                'empresa_nombre' => $user['company']['name'],
                'lote_id' => $lote_id
            ]);
        }

        return 'Vendedores importados correctamente';
    }

    public function disponibles()
    {
        $response = Http::get('https://jsonplaceholder.typicode.com/users');
        return $response->json();
    }

    public function importarUno($email, $lote_id)
    {
        $response = Http::get('https://jsonplaceholder.typicode.com/users');
        $users = $response->json();

        foreach ($users as $user) {
            if ($user['email'] === $email) {

                if (Vendedor::where('email', $user['email'])->exists()) {
                    throw new \Exception('Ya existe');
                }

                return Vendedor::create([
                    'nombre' => $user['name'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'telefono' => $user['phone'],
                    'website' => $user['website'],
                    'calle' => $user['address']['street'],
                    'ciudad' => $user['address']['city'],
                    'zipcode' => $user['address']['zipcode'],
                    'latitud' => $user['address']['geo']['lat'],
                    'longitud' => $user['address']['geo']['lng'],
                    'empresa_nombre' => $user['company']['name'],
                    'lote_id' => $lote_id
                ]);
            }
        }

        throw new \Exception('No encontrado');
    }
}