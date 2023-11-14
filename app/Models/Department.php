<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = [
        'name', 'code'
    ];

    public function agents(): HasMany
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }

    public function department_types(): HasMany
    {
        return $this->hasMany(DepartmentType::class, 'department_id', 'id');
    }

    public function scopeFilterDepartments(Builder $builder): Builder
    {
        return $builder->when(request()->input('name'), function(Builder $q, $name) {
            $q->where('name', 'LIKE', '%' . $name . '%');
        });
    }
}
