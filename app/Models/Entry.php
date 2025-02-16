<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entry extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',   // Ajout de user_id ici
        'type',
        'motif',
        'latitude',
        'longitude',
        'heure_sortie',
        'heure_retour',
    ];
}
