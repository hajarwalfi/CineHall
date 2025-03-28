<?php

namespace App\Http\Controllers;

use App\Services\FilmService;
use Illuminate\Http\Request;

class FilmController extends Controller
{
    protected $filmService;

    public function __construct(FilmService $filmService)
    {
        $this->filmService = $filmService;
    }

    public function show($filmId)
    {
        $film = $this->filmService->getFilmById($filmId);
        $seances = $this->seanceService->getSeancesByFilm($filmId);
        return response()->json([
            'film' => $film,
            'seances' => $seances,
        ]);
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
