<?php

namespace App\Http\Controllers;

use App\Services\SalleService;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    protected $salleService;

    public function __construct(SalleService $salleService)
    {
        $this->salleService = $salleService;
    }

    public function index()
    {
        return response()->json($this->salleService->getAllSalles());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string',
            'capacite' => 'required|integer|min:1',
        ]);

        $salle = $this->salleService->createSalle($validated);
        return response()->json($salle, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'nom' => 'sometimes|required|string',
            'capacite' => 'sometimes|required|integer|min:1',
        ]);

        $salle = $this->salleService->updateSalle($id, $validated);
        return response()->json($salle);
    }

    public function destroy($id)
    {
        $this->salleService->deleteSalle($id);
        return response()->json(null, 204);
    }
}

