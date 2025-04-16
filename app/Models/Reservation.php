<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = ['seance_id', 'siege_id', 'user_id', 'statut', 'code', 'expire_at'];

    protected $dates = ['expire_at', 'created_at', 'updated_at'];

    public function seance()
    {
        return $this->belongsTo(Seance::class);
    }

    public function siege()
    {
        return $this->belongsTo(Siege::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Vérifier si la réservation est expirée
    public function isExpired()
    {
        return $this->statut === 'Réservé' && $this->expire_at && Carbon::now()->gt($this->expire_at);
    }

    // Générer un code de réservation unique
    public static function generateUniqueCode()
    {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';

        do {
            $code = '';
            for ($i = 0; $i < 8; $i++) {
                $code .= $characters[rand(0, strlen($characters) - 1)];
            }
        } while (self::where('code', $code)->exists());

        return $code;
    }
}
