<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;
    protected $fillable = ['titre','description', 'photo','duree', 'ageMin','bandeAnnonce','genre'];

    public function seances(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(Seance::class);
    }
}
