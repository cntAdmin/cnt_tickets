<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use SoftDeletes;
    
    protected $fillable = [
        'cif', 'name', 'alias', 'email', 'address', 'town', 'postcode', 'phone', 'fax', 'is_active', 'deleted_by', 'deleted_at'
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'customer_id', 'id');
    }

    public function scopeFilterCustomers(Builder $builder): Builder
    {
        // return $builder->when(request()->input('customer_id'), function(Builder $q, $customer_id) {
        //         $q->where('id', $customer_id);
        //     })->when(request()->input('cif'), function(Builder $q, $cif) {
        //         $q->where('cif', 'LIKE', '%'. $cif . '%');
        //     })->when(request()->input('name'), function(Builder $q, $name) {
        //         $q->where(function(Builder $q2) use ($name) {
        //             $q2->where('name', 'LIKE', '%' . $name . '%')->orWhere('alias', 'LIKE', '%' . $name . '%');
        //         });
        //     })->when(request()->input('email'), function(Builder $q, $email) {
        //         $q->where('email', 'LIKE', '%' .$email . '%');
        //     })->when(request()->input('address'), function(Builder $q, $address) {
        //         $q->where('address', 'LIKE', '%' .$address . '%');
        //     })->when(request()->input('town'), function(Builder $q, $town) {
        //         $q->where('town', 'LIKE', '%' .$town . '%');
        //     })->when(request()->input('phone'), function(Builder $q, $phone) {
        //         $q->where('phone', 'LIKE', '%' .$phone . '%');
        //     })->when(request()->input('fax'), function(Builder $q, $fax) {
        //         $q->where('fax', 'LIKE', '%' .$fax . '%');
        //     });
        return $builder->when(request()->input('name'), function(Builder $q, $name) {
            $q->where(function(Builder $q2) use ($name) {
                $q2->where('name', 'LIKE', '%' . $name . '%')->orWhere('alias', 'LIKE', '%' . $name . '%');
            });
        })->when(request()->input('email'), function(Builder $q, $email) {
            $q->where('email', 'LIKE', '%' .$email . '%');
        })->when(request()->input('is_active'), function(Builder $q, $is_active) {
            $is_active == 1 ? $q->where('is_active', 1) : $q->where('is_active', 0);
        });
    }
}
