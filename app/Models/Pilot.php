<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pilot extends Model
{
    //use HasFactory;
    /**
     * Creamos los atributos que queramos guardar masivos
     */
    protected $fillable = [
        'id',
        'name',
        'height',
        'mass',
        'hair_color',
        'skin_color',
        'eye_color',
        'birth_year',
        'gender',
        'homeworld',
        'created',
        'edited',
        'url',
    ];
    public function ships()
    {
        return $this->belongsToMany(Starship::class, 'pilot_ship', 'pilot_id', 'starship_id');
    }
}
