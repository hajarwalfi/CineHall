<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;



class Reservation extends Model
{
    protected $fillable = ['user_id', 'seance_id', 'statut', 'expiration_at'];
}
