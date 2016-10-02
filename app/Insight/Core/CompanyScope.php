<?php

namespace Insight\Core;

use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\ScopeInterface;

class CompanyScope implements ScopeInterface
{

    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @return void
     */
    public function apply(Builder $builder)
    {
        $user = Sentry::getUser();
        if ($user) {
            // if current user is not a member of the site owner company, apply scope
            if ($user->company->id !== (int) settings('site_owner')) {
                $builder->where('company_id', $user->company->id);

                // user is not a site owner, restrict access to his own data
                if (!$user->hasAccess('customers.data')) {
                    $builder->where('created_by_id', $user->id);
                }
            }
        }

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
        $bindingKey = 0;

        foreach ((array) $query->wheres as $key => $where) {

            if ($where['column'] == 'company_id') {

                unset($query->wheres[$key]);
                $query->wheres = array_values($query->wheres);
                $this->removeBinding($query, $key);
            }
            if ($where['column'] == 'created_by_id') {

                unset($query->wheres[$key]);
                $query->wheres = array_values($query->wheres);
                $this->removeBinding($query, $key);
            }
            // Check if where is either NULL or NOT NULL type,
            // if that's the case, don't increment the key
            // since there is no binding for these types
            if ( ! in_array($where['type'], ['Null', 'NotNull'])) $bindingKey++;
        }
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  int $key
     * @return void
     */
    protected function removeWhere($query, $key)
    {
        unset($query->wheres[$key]);

        $query->wheres = array_values($query->wheres);
    }

    /**
     * Remove scope constraint from the query.
     *
     * @param  \Illuminate\Database\Eloquent\Builder $builder
     * @param  int $key
     * @return void
     */
    protected function removeBinding($query, $key)
    {
        $bindings = $query->getRawBindings()['where'];

        unset($bindings[$key]);

        $query->setBindings(array_values($bindings));
    }
}
