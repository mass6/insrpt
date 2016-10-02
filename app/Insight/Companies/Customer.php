<?php

namespace Insight\Companies;

use Insight\Companies\Scopes\CustomerCompanyScope;

/**
 * Class Customer
 * @package Insight
 */
class Customer extends Company
{

    /**
     * @var string
     */
    protected $table = 'companies';

    /**
     *
     */
    public static function boot()
    {
        parent::boot();
        static::addGlobalScope(new CustomerCompanyScope);
    }

    /**
     * @return mixed
     */
    public function suppliers()
    {
        return $this->hasMany('Insight\Companies\Supplier', 'company_id');
    }

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->hasMany('Insight\Users\User', 'company_id');
    }
}
