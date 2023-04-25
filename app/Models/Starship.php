<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Starship extends Model
{
    //use HasFactory;
    protected $table = 'starships';
    /**
     * Creamos los atributos que queramos guardar masivos
     */
    protected $fillable = [
        'id',
        'name',
        'model',
        'manufacturer',
        'cost_in_credits',
        'length',
        'max_atmosphering_speed',
        'crew',
        'passengers',
        'cargo_capacity',
        'consumables',
        'hyperdrive_rating',
        'MGLT',
        'starship_class',
        'created',
        'edited',
        'url',
    ];
    public function pilots()
    {
        return $this->belongsToMany(Pilot::class, 'pilot_ship', 'starship_id', 'pilot_id');
    }
}
