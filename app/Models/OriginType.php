<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OriginType extends Model
{
    protected $fillable = [
        'name'
    ];
    
    /**
     * Get all of the tickets for the OriginType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'origin_type_id', 'id');
    }

    public function scopeFilterOriginTypes(Builder $builder): Builder
    {
        return $builder->when(request()->input('name'), function(Builder $q, $name) {
                    $q->where('name', 'LIKE', '%'. $name . '%');
            });
    }
}
