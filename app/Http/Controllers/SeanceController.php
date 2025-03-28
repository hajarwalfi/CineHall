<?php

namespace App\Http\Controllers;

use App\Models\Seance;
use App\Services\SeanceService;
use Illuminate\Http\Request;

class SeanceController extends Controller
{
    protected $seanceService;

    public function __construct(SeanceService $seanceService)
    {
        $this->seanceService = $seanceService;
    }


    public function index(Request $request)
    {
        $type = $request->input('type');
        if ($type) {
            $seances = $this->seanceService->getSeancesByType($type);
        } else {
            $seances = $this->seanceService->getAllSeances();
        }

        return response()->json($seances);
    }

    public function show($id)
    {
        $seance = $this->seanceService->getSeanceById($id);
        return response()->json($seance);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'film_id' => 'required|exists:films,id',
            'startTime' => 'required|date',
            'endTime' => 'required|date',
            'langue' => 'required|string',
            'type' => 'required|in:Normal,VIP',
            'salle_id' => 'required|exists:salles,id'
        ]);

        $seance = $this->seanceService->createSeance($validated);
        return response()->json($seance, 201);
    }

    // Modifier une séance
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'startTime' => 'sometimes|date',
            'endTime' => 'sometimes|date',
            'langue' => 'sometimes|string',
            'type' => 'sometimes|in:Normal,VIP',
            'salle_id' => 'exists:salles,id',
            'seance_id' => 'exists:seance_id',
        ]);

        $seance = $this->seanceService->updateSeance($id, $validated);
        return response()->json($seance);
    }

    // Supprimer une séance
    public function destroy($id)
    {
        $this->seanceService->deleteSeance($id);
        return response()->json(null, 204);
    }

    // Récupérer les séances d'un film
    public function getByFilm($filmId)
    {
        return response()->json($this->seanceService->getSeancesByFilm($filmId));
    }
}
