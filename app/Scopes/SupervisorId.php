<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Facades\Auth;

class SupervisorId implements Scope
{
    public function apply(Builder $builder, Model $model)
    {
        $builder->where('supervisor_id', '=', Auth::guard('supervisor')->id());
    }
}
