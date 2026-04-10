<?php

namespace App\Http\Controllers;

use App\Services\VendedorService;
use Illuminate\Http\Request;

class VendedorController extends Controller
{
    protected $vendedorService;

    public function __construct(VendedorService $vendedorService)
    {
        $this->vendedorService = $vendedorService;
    }

    public function index()
    {
        return response()->json($this->vendedorService->getAll());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:vendedores',
            'lote_id' => 'required|exists:lotes,id'
        ]);

        $vendedor = $this->vendedorService->create($request->all());

        return response()->json(['message' => 'Creado', 'vendedor' => $vendedor], 201);
    }

    public function show($id)
    {
        return response()->json($this->vendedorService->find($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:vendedores,email,' . $id,
            'lote_id' => 'required|exists:lotes,id'
        ]);

        $vendedor = $this->vendedorService->update($id, $request->all());

        return response()->json(['message' => 'Actualizado', 'vendedor' => $vendedor]);
    }

    public function destroy($id)
    {
        $this->vendedorService->delete($id);
        return response()->json(['message' => 'Eliminado']);
    }

    public function importar($lote_id)
    {
        return response()->json(['message' => $this->vendedorService->importar($lote_id)]);
    }

    public function disponibles()
    {
        return response()->json($this->vendedorService->disponibles());
    }

    public function importarUno(Request $request)
    {
        try {
            $vendedor = $this->vendedorService->importarUno($request->email, $request->lote_id);
            return response()->json($vendedor);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }
}