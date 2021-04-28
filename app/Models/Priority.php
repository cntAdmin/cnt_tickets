<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Priority extends Model
{
    protected $connection = "mysql";
    protected $fillable = [
        'name'
    ];
    
    /**
     * Get all of the tickets for the Priority
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'priority_id', 'id');
    }
}
