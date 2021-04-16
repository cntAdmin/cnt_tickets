<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ByRoleOnCustomerIDGlobalScope implements Scope
{
    private $searchRoleKey;

    public function __construct(string $searchRoleKey)
    {
        $this->searchRoleKey = $searchRoleKey;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $builder
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return void
     */
    public function apply(Builder $builder, Model $model): Builder
    {
        $user = auth()->user();
        if(auth()->check()) {
            switch($user->roles[0]->id) {
                case 3:
                    return $builder->where($this->searchRoleKey, $user->customer_id);
                    break;
                case 4:
                    return $builder->where([$this->searchRoleKey => $user->customer_id, 'user_id' => $user->id]);
                    break;
                default:
                    return $builder;
                break;
            }
        }
    }
}
