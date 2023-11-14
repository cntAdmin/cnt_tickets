<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachment extends Model
{
    protected $fillable = [
        'name', 'path', 'created_at', 'updated_at'
    ];

    public function tickets()
    {
        return $this->morphedByMany(Ticket::class, 'attachable')->withTimestamps();
    }

    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'attachable')->withTimestamps();
    }
}
