<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    protected $fillable = [
        'name'
    ];
    
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'priority_id', 'id');
    }
}
