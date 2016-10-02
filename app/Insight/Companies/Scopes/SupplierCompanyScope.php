<?php

namespace Insight\Companies\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class SupplierCompanyScope implements ScopeInterface
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $builder->where('type', 'supplier');
    }


    /**
     * Remove the scope from the given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function remove(Builder $builder)
    {
        $query = $builder->getQuery();

        foreach ((array) $query->wheres as $key => $where) {
            if ($where['column'] == 'type') {
                unset($query->wheres[$key]);

                $query->wheres = array_values($query->wheres);
            }
        }
    }
}
