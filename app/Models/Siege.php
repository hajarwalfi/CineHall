<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siege extends Model
{
    use HasFactory;

    protected $fillable = ['salle_id', 'rangee', 'numero', 'type', 'est_couple_avec'];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    // Récupérer le siège associé (pour les sièges en couple)
    public function siegeCouple()
    {
        if ($this->est_couple_avec) {
            return Siege::find($this->est_couple_avec);
        }
        return null;
    }
}
