<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'username', 'mtcdr_customer_name', 'email', 'password', 'is_active', 'deleted_by', 'deleted_at', 'created_at',
        'updated_at', 'customer_id', 'created_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $with = [
        'customer', 'roles'
    ];

    /**
     * Get the customer that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }

    /**
     * Get all of the tickets for the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets(): HasMany
    {
        return $this->hasMany(Ticket::class, 'user_id', 'id');
    }

    /**
     * Get the department that owns the User
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'department_id', 'id');
    }

    public function scopeFilterUsers(Builder $builder): Builder
    {
        return $builder->when(request()->input('customer_id'), function(Builder $q, $customer_id) {
                $q->where('customer_id', $customer_id);
            })->when(request()->input('department_id'), function(Builder $q, $department_id) {
                $q->where('department_id', $department_id);
            })->when(request()->input('name'), function(Builder $q, $name) {
                $q->where('name', 'LIKE', '%' . $name . '%');
            })->when(request()->input('username'), function(Builder $q, $username) {
                $q->where('username', 'LIKE', '%' . $username . '%');
            })->when(request()->input('email'), function(Builder $q, $email) {
                $q->where('email', 'LIKE', '%' . $email . '%');
            })->when(request()->input('role_id'), function(Builder $q, $role_id) {
                $q->whereHas('roles', function(Builder $q2) use ($role_id) {
                    $q2->where('roles.id', $role_id);
                });
            });
    }
}
