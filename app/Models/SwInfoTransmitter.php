<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SwInfoTransmitter extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    public $guarded = ['id'];




    public function swInfoBroadcasts(): HasMany
    {
        return $this->hasMany(SwInfoBroadcast::class);
    }

}
