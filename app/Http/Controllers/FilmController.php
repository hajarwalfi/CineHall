<?php

namespace App\Http\Controllers;

use App\Services\FilmService;
use App\Services\SeanceService;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    protected $filmService;
    protected $seanceService; // Ajout du service manquant

    public function __construct(FilmService $filmService, SeanceService $seanceService)
    {
        $this->filmService = $filmService;
        $this->seanceService = $seanceService; // Injection du service
    }

    public function index()
    {
        // Utiliser le service au lieu du modèle directement
        $films = $this->filmService->getAllFilms();
        return response()->json($films);
    }

    public function show($filmId)
    {
        try {
            $film = $this->filmService->getFilmById($filmId);

            // Vérifier que $film n'est pas null
            if (!$film) {
                return response()->json(['error' => 'Film non trouvé'], 404);
            }

            $seances = $this->seanceService->getSeancesByFilm($filmId);

            return response()->json([
                'film' => $film,
                'seances' => $seances,
            ]);
        } catch (\Exception $e) {
            // Log l'erreur et renvoyer une réponse JSON d'erreur
            \Log::error('Erreur dans FilmController@show: ' . $e->getMessage());
            return response()->json(['error' => 'Une erreur est survenue: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'required|string',
            'duree' => 'required|integer',
            'ageMin' => 'required|string',
            'bandeAnnonce' => 'nullable|string',
            'genre' => 'required|string',
        ]);

        $film = $this->filmService->createFilm($data);
        return response()->json($film, 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'titre' => 'string|max:255',
            'description' => 'string',
            'photo' => 'string',
            'duree' => 'integer',
            'ageMin' => 'string',
            'bandeAnnonce' => 'nullable|string',
            'genre' => 'string',
        ]);

        $film = $this->filmService->updateFilm($id, $data);
        return response()->json($film);
    }

    public function destroy($id)
    {
        $this->filmService->deleteFilm($id);
        return response()->json(['message' => 'Film supprimé avec succès']);
    }
}
