<?php
// app/Http/Controllers/SiegeController.php
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
        $sieges = $this->siegeService->getAllSieges();
        return response()->json($sieges);
    }

    public function show($id)
    {
        $siege = $this->siegeService->getSiegeById($id);
        return response()->json($siege);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'rangee' => 'required|string|max:5',
            'numero' => 'required|integer|min:1',
            'type' => 'required|in:Standard,VIP,Couple',
            'est_couple_avec' => 'nullable|exists:sieges,id'
        ]);

        $siege = $this->siegeService->createSiege($validated);
        return response()->json($siege, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'rangee' => 'string|max:5',
            'numero' => 'integer|min:1',
            'type' => 'in:Standard,VIP,Couple',
            'est_couple_avec' => 'nullable|exists:sieges,id'
        ]);

        $siege = $this->siegeService->updateSiege($id, $validated);
        return response()->json($siege);
    }

    public function destroy($id)
    {
        $this->siegeService->deleteSiege($id);
        return response()->json(['message' => 'Siège supprimé avec succès']);
    }

    public function getBySalle($salleId)
    {
        $sieges = $this->siegeService->getSiegesBySalle($salleId);
        return response()->json($sieges);
    }

    public function createCoupleSeats(Request $request)
    {
        $validated = $request->validate([
            'salle_id' => 'required|exists:salles,id',
            'rangee' => 'required|string|max:5',
            'numero_debut' => 'required|integer|min:1'
        ]);

        $sieges = $this->siegeService->createCoupleSeats(
            $validated['salle_id'],
            $validated['rangee'],
            $validated['numero_debut']
        );

        return response()->json($sieges, 201);
    }
}
