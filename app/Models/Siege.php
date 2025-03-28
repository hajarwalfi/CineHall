<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Siege extends Model
{
    use HasFactory;

    protected $table = 'sieges';

    protected $fillable = ['salle_id', 'numero', 'statut', 'seance_id'];

    public function salle()
    {
        return $this->belongsTo(Salle::class);
    }

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }


}
