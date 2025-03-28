<?php


namespace App\Http\Controllers;

use App\Services\SiegeService;
use Illuminate\Http\Request;

class SiegeController extends Controller
{
    protected $siegeService;

    public function __construct(SiegeService $siegeService)
    {
        $this->siegeService = $siegeService;
    }

    public function index()
    {
        $sieges = $this->siegeService->allSieges();
        return response()->json($sieges);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'numero' => 'required|string',
            'statut' => 'required|string',
            'seance_id' => 'required|exists:seances,id'
        ]);

        $siege = $this->siegeService->createSiege($data);
        return response()->json($siege, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'salle_id' => 'exists:salles,id',
            'numero' => 'string',
            'statut' => 'required|string',
        ]);

        $siege = $this->siegeService->updateSiege($id, $data);
        return response()->json($siege);
    }

    public function destroy($id)
    {
        $this->siegeService->deleteSiege($id);
        return response()->json(['message' => 'Siege supprimé avec succès']);
    }
    public function getSiegesDisponibles($seanceId)
    {
        $sieges = $this->siegeService->getSiegesDisponibles($seanceId);
        return response()->json($sieges);
    }
    public function reserver(Request $request)
    {
        $data = $request->validate([
            'siege_id' => 'required|exists:sieges,id',
            'seance_id' => 'required|exists:seances,id'
        ]);

        try {
            $reservation = $this->siegeService->reserverSiege($data['siege_id'], $data['seance_id']);
            return response()->json(['message' => 'Réservation réussie', 'data' => $reservation], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        }
    }




}
