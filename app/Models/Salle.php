<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salle extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'capacity'];

    public function seances()
    {
        return $this->hasMany(Seance::class);
    }
}
