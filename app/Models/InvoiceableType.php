<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class InvoiceableType extends Model
{
    protected $fillable = [
        'name'
    ];

    /**
     * Get all of the tickets for the InvoiceableType
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'invoiceable_type_id', 'id');
    }

}
