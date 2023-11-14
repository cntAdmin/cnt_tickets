<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    protected $fillable = [
        'name', 'username', 'mtcdr_customer_name', 'email', 'password', 'is_active', 'deleted_by', 'deleted_at', 'created_at',
        'updated_at', 'customer_id', 'created_by'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'customer', 'roles'
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id', 'id');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function scopeFilterUsers(Builder $builder): Builder
    {
        return $builder->when(request()->input('customer_id'), function(Builder $q, $customer_id) {
                $q->where('customer_id', $customer_id);
            })->when(request()->input('name'), function(Builder $q, $name) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            })->when(request()->input('email'), function(Builder $q, $email) {
                $q->where('email', 'LIKE', '%' . $email . '%');
            })->when(request()->input('role_id'), function(Builder $q, $role_id) {
                $q->whereHas('roles', function(Builder $q2) use ($role_id) {
                    $q2->where('roles.id', $role_id);
                });
            })->when(request()->input('is_active'), function(Builder $q, $is_active) {
                $q->where('is_active', $is_active == 1 ? true : false);
            });
    }
}
