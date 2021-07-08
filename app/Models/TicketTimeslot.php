<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TicketTimeslot extends Model
{
    protected $fillable = [
        'start_date_time', 'end_date_time', 'work_time',
        // RELATIONS
        'ticket_id'
    ];
    protected $appends = [
        'start_date_time_picker', 'end_date_time_picker'
    ];

    public function getStartDateTimePickerAttribute()
    {
        return $this->attributes['start_date_time']
            ? Carbon::parse($this->attributes['start_date_time'])->format('Y-m-d\TH:i')
            : null ;
    }
    public function getEndDateTimePickerAttribute()
    {
        return $this->attributes['end_date_time']
            ? Carbon::parse($this->attributes['end_date_time'])->format('Y-m-d\TH:i')
            : null ;
    }
    /**
     * Get the ticket that owns the TicketTimeslot
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }
}
