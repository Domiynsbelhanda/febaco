<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    protected $fillable = [
        'entity_id',
        'user_id',
        'name',
        'description',
        'responsible_name',
        'contact_email',
        'contact_phone',
        'address',
        'logo',
        'is_active',
    ];

    public function entity()
    {
        return $this->belongsTo(Entity::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function athletes()
    {
        return $this->hasMany(Athlete::class);
    }

}
