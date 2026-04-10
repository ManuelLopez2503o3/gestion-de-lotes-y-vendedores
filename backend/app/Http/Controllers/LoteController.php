<?php

namespace App\Http\Controllers;

use App\Services\LoteService;
use Illuminate\Http\Request;

class LoteController extends Controller
{
    protected $loteService;

    public function __construct(LoteService $loteService)
    {
        $this->loteService = $loteService;
    }

    public function index()
    {
        return response()->json($this->loteService->getAll());
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'identificador' => 'required|string|unique:lotes,identificador',
        ]);

        $lote = $this->loteService->create($request->all());

        return response()->json(['message' => 'Lote creado', 'lote' => $lote], 201);
    }

    public function show($id)
    {
        return response()->json($this->loteService->find($id));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'direccion' => 'required|string|max:255',
            'identificador' => 'required|string|unique:lotes,identificador,' . $id,
        ]);

        $lote = $this->loteService->update($id, $request->all());

        return response()->json(['message' => 'Actualizado', 'lote' => $lote]);
    }

    public function destroy($id)
    {
        try {
            $this->loteService->delete($id);
            return response()->json(['message' => 'Eliminado']);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    public function importarVendedores(Request $request, $id)
    {
        try {
            $mensaje = $this->loteService->importarVendedores($id, $request->cantidad);
            return response()->json(['message' => $mensaje]);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }
    }

    public function vendedoresPorLote($id)
    {
        return response()->json($this->loteService->vendedoresPorLote($id));
    }

    public function quitarVendedor($id)
    {
        $this->loteService->eliminarVendedor($id);
        return response()->json(['message' => 'Vendedor eliminado']);
    }

    public function actualizarVendedor(Request $request, $id)
    {
        $vendedor = $this->loteService->actualizarVendedor($id, $request->only(['nombre', 'lote_id']));
        return response()->json(['message' => 'Actualizado', 'vendedor' => $vendedor]);
    }
}