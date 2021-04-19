<?php

namespace App\Models;

use App\Scopes\ByRoleOnCustomerIDGlobalScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Str;

class Ticket extends Model
{
    use SoftDeletes;
    
    protected static function booted()
    {
        static::addGlobalScope(new ByRoleOnCustomerIDGlobalScope('custom_id'));
    }

    protected $fillable = [
        'title', 'description', 'deleted_at', 'read_by_admin', 'custom_id',
        // ASSOCIATIONS
        'deleted_by', 'department_type_id', 'ticket_status_id'
    ];
    protected $with = [
        'customer', 'user', 'agent', 'department_type', 'priority', 'ticket_status', 'origin_type', 'createdBy'
    ];
    
    protected $appends = ['description_short'];

    public function getDescriptionShortAttribute()
    {
        return Str::limit($this->attributes['description'], 100);
    }
    
    /**
     * Get the created_by that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    /**
     * Get the agent that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'agent_id', 'id');
    }

    /**
     * Get the customer that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get all of the tags for the post.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attachments():MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachable');
    }
    
    /**
     * Get the user that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the department_type that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department_type(): BelongsTo
    {
        return $this->belongsTo(DepartmentType::class, 'department_type_id', 'id');
    }

    /**
     * Get the priority that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority(): BelongsTo
    {
        return $this->belongsTo(Priority::class, 'priority_id', 'id');
    }

    /**
     * Get the origin_type that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function origin_type(): BelongsTo
    {
        return $this->belongsTo(OriginType::class, 'origin_type_id', 'id');
    }

    /**
     * Get the ticket_type that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket_type(): BelongsTo
    {
        return $this->belongsTo(TicketType::class, 'ticket_type_id', 'id');
    }

    /**
     * Get the deleted_by that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deleted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }

    /**
     * Get the ticket_status that owns the Ticket
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket_status(): BelongsTo
    {
        return $this->belongsTo(TicketStatus::class, 'ticket_status_id', 'id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class, 'ticket_id', 'id');
    }

    public function scopeFilterTickets(Builder $query): Builder
    {
        return $query->when(request()->input('ticket_id'), function(Builder $q, $ticket_id) {
                return $q->where('custom_id', '%' . $ticket_id . '%');
            })->when(request()->input('user_id'), function(Builder $q, $user_id) {
                return $q->whereHas('user', function($q2) use ($user_id) {
                    $q2->where('id', $user_id);
                });
            })->when(request()->input('customer_id'), function(Builder $q, $customer_id) {
                return $q->where('customer_id', $customer_id);
            })->when(request()->input('agent_id'), function(Builder $q, $agent_id) {
                return $q->where('agent_id', $agent_id);
            })->when(request()->input('department_type_id'), function(Builder $q, $department_type_id) {
                return $q->where('department_type_id', $department_type_id);
            })->when(request()->input('priority_id'), function(Builder $q, $priority_id) {
                return $q->where('priority_id', $priority_id);
            })->when(request()->input('priority_id'), function(Builder $q, $priority_id) {
                return $q->where('priority_id', $priority_id);
            })->when(request()->input('origin_type_id'), function(Builder $q, $origin_type_id) {
                return $q->where('origin_type_id', $origin_type_id);
            })->when(request()->input('ticket_type_id'), function(Builder $q, $ticket_type_id) {
                return $q->where('ticket_type_id', $ticket_type_id);
            })->when(request()->input('ticket_status_id'), function(Builder $q, $ticket_status_id) {
                switch ($ticket_status_id) {
                    case 1:
                        return $q->where(function(Builder $q2) use ($ticket_status_id) {
                            $q2->where('ticket_status_id', $ticket_status_id)->orWhere('read_by_admin', in_array(auth()->user()->roles[0]->id, [1, 2]) ? false : true);
                        });
                        break;
                    
                    default:
                        return $q->where('ticket_status_id', $ticket_status_id);
                        break;
                }
            })->when(request()->input('dateFrom'), function(Builder $q, $dateFrom) {
                return $q->whereDate('created_at', '>=', $dateFrom);
            })->when(request()->input('dateTo'), function(Builder $q, $dateTo) {
                return $q->whereDate('created_at', '<=', $dateTo);
            })->when(request()->input('text'), function(Builder $q, $description) {
                return $q->where(function(Builder $q2) use ($description) {
                    $q2->where('title', 'LIKE', '%' . $description . '%')
                        ->orWhere('description', 'LIKE', '%' . $description . '%');
                });
            })->when(request()->input('read_by_admin'), function(Builder $q, $read_by_admin) {
                switch($read_by_admin) {
                    case 1:
                        $q->where('read_by_admin', true);
                        break;
                    default:
                        $q->where('read_by_admin', false);
                        break;
                }
            });
    }

}
