<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id',
        'from_team_id',
        'to_team_id',
        'initiated_by_id',
        'transfer_date',
        'type',
        'status',
        'confirmation_by_destination',
        'confirmation_by_federation',
        'notes',
    ];

    public function athlete() {
        return $this->belongsTo(Athlete::class);
    }

    public function fromTeam() {
        return $this->belongsTo(Team::class, 'from_team_id');
    }

    public function toTeam() {
        return $this->belongsTo(Team::class, 'to_team_id');
    }

    public function initiatedBy() {
        return $this->belongsTo(User::class, 'initiated_by_id');
    }

}
