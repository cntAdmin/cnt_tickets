<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DepartmentType extends Model
{
    protected $fillable = [
        'name'
    ];
    protected $with = [
        'department'
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'department_type_id', 'id');
    }

    public function scopeFilterDepartmentTypes(Builder $builder): Builder
    {
        return $builder->when(request()->input('department_id'), function(Builder $q, $department_id) {
                $q->where('department_id', $department_id);
            })->when(request()->input('name'), function(Builder $q, $name) {
                $q->where('name', 'LIKE', '%'. $name . '%');
            });
    }
}
