<?php
// app/Http/Controllers/SalleController.php
namespace App\Http\Controllers;

use App\Services\SalleService;
use App\Services\SiegeService;
use Illuminate\Http\Request;

class SalleController extends Controller
{
    protected $salleService;
    protected $siegeService;

    public function __construct(SalleService $salleService, SiegeService $siegeService)
    {
        $this->salleService = $salleService;
        $this->siegeService = $siegeService;
    }

    public function index()
    {
        $salles = $this->salleService->getAllSalles();
        return response()->json($salles);
    }

    public function show($id)
    {
        $salle = $this->salleService->getSalleById($id);
        $sieges = $this->siegeService->getSiegesBySalle($id);

        return response()->json([
            'salle' => $salle,
            'sieges' => $sieges
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero' => 'required|string|max:10',
            'nom' => 'required|string|max:255',
            'capacite' => 'required|integer|min:1',
            'supporte_vip' => 'boolean'
        ]);

        $salle = $this->salleService->createSalle($validated);

        // Si la configuration automatique est demandée
        if ($request->has('auto_config') && $request->auto_config) {
            $nbRangees = $request->nb_rangees ?? 10;
            $siegesParRangee = $request->sieges_par_rangee ?? 15;

            $this->siegeService->genererConfigurationSalle(
                $salle->id,
                $nbRangees,
                $siegesParRangee,
                $salle->supporte_vip
            );
        }

        return response()->json($salle, 201);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'numero' => 'string|max:10',
            'nom' => 'string|max:255',
            'capacite' => 'integer|min:1',
            'supporte_vip' => 'boolean'
        ]);

        $salle = $this->salleService->updateSalle($id, $validated);
        return response()->json($salle);
    }

    public function destroy($id)
    {
        $this->salleService->deleteSalle($id);
        return response()->json(['message' => 'Salle supprimée avec succès']);
    }

    public function vipSalles()
    {
        $salles = $this->salleService->getSallesWithVIP();
        return response()->json($salles);
    }
}
