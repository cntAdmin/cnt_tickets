<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TicketStatus extends Model
{
    protected $connection = "mysql";
    protected $fillable = [
        'name', 'color', 'icon'
    ];

    /**
     * Get all of the tickets for the TicketStatus
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'ticket_status_id', 'id');
    }

    public function scopeFilterTicketStatuses(Builder $builder): Builder
    {
        return $builder->when(request()->input('name'), function(Builder $q, $name) {
            $q->where('name', 'LIKE', '%' . $name . '%');
        });
    }
}
