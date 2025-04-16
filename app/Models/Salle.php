<?php
// app/Models/Salle.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = ['numero', 'nom', 'capacite', 'supporte_vip'];

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }

    public function sieges()
    {
        return $this->hasMany(Siege::class);
    }
}
