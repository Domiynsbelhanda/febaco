<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Federation extends Model
{
    use HasFactory;


    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    public function entities()
    {
        return $this->hasMany(Entity::class);
    }

}
