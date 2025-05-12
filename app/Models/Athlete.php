<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Athlete extends Model
{
    use HasFactory;

    protected $fillable = [
        'team_id',
        'last_name',
        'middle_name',
        'first_name',
        'birth_date',
        'birth_place',
        'nationality',
        'gender',
        'matricule',
        'photo',
        'height',
        'weight',
        'position',
        'jersey_number',
        'contact_email',
        'contact_phone',
        'is_active',
    ];

    public function team()
    {
        return $this->belongsTo(Team::class);
    }
}
