<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name', 'path', 'created_at', 'updated_at'
    ];

    /**
     * Get all of the posts that are assigned this tag.
     * 
     * @return MorphedByMany
     */
    public function tickets()
    {
        return $this->morphedByMany(Ticket::class, 'attachable')->withTimestamps();
    }

    /**
     * Get all of the videos that are assigned this tag.
     * 
     * @return MorphedByMany
     */
    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'attachable')->withTimestamps();
    }
}
