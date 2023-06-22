<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StationType extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $guarded = ['id'];



    public function stations(): HasMany
    {
        return $this->hasMany(Station::class);
    }

}
