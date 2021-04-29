<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attachable extends Model
{
    public function comments()
    {
        return $this->morphedByMany(Comment::class, 'attachable');
    }
    public function tickets()
    {
        return $this->morphedByMany(Ticket::class, 'attachable');
    }
}
