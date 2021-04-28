<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model
{
    use SoftDeletes;
    
    protected $connection = "mysql";
    protected $fillable = [
        'description', 'description', '_token', 'expires_in', 'deleted_by', 'deleted_at',
        // RELACIONES
        'ticket_id', 'user_id'
    ];

    protected $with = [
        'user', 'attachments'
    ];

    /**
     * Get all of the tags for the post.
     * 
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function attachments(): MorphToMany
    {
        return $this->morphToMany(Attachment::class, 'attachable');
    }

    /**
     * Get the ticket that owns the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class, 'ticket_id', 'id');
    }

    /**
     * Get the user that owns the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Get the deleted_by that owns the Comment
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function deleted_by(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by', 'id');
    }
}
