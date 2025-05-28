<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Performance extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'date',
        'event',
        'score',
        'position',
        'observation',
        'recorded_by'
    ];

    public function athlete()
    {
        return $this->belongsTo(Athlete::class);
    }

    public function recorder()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
