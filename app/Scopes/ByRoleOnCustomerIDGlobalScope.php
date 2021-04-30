<?php

namespace App\Scopes;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Http\JsonResponse;

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
    public function apply(Builder $builder, Model $model)
    {
        $user = auth()->user();
        if(auth()->check()) {
            dd($user->roles[0]->id);
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
