<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $connection = "mysql";
    protected $fillable = [
        'name', 'code'
    ];

    /**
     * Get all of the agents for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function agents(): HasMany
    {
        return $this->hasMany(User::class, 'department_id', 'id');
    }
    /**
     * Get all of the department_type for the Department
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function department_types(): HasMany
    {
        return $this->hasMany(DepartmentType::class, 'department_id', 'id');
    }

    public function scopeFilterDepartments(Builder $builder): Builder
    {
        return $builder->when(request()->input('name'), function(Builder $q, $name) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            })->when(request()->input('code'), function(Builder $q, $code) {
                $q->where('code', 'LIKE', '%' . $code . '%');
            })->when(request()->input('agent_id'), function(Builder $q, $agent_id) {
                $q->whereHas('agents', function(Builder $q) use ($agent_id) {
                    $q->where('id', $agent_id);
                });
            });
    }
}
