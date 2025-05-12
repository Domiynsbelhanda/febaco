<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    use HasFactory;

    protected $fillable = [
        'federation_id',
        'name',
        'description',
        'region',
        'responsible_name',
        'contact_email',
        'contact_phone',
        'address',
        'logo',
        'is_active',
    ];

    public function federation()
    {
        return $this->belongsTo(Federation::class);
    }

    public function teams()
    {
        return $this->hasMany(Team::class);
    }


}
