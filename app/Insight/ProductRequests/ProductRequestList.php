<?php

namespace Insight\ProductRequests;

use Insight\Core\CompanyScope;
use Laracasts\Commander\Events\EventGenerator;

/**
 * Class ProductRequestList
 * @package Insight\ProductRequests
 */
class ProductRequestList extends \Eloquent
{

    use EventGenerator;

    /**
     * @var array
     */
    protected $fillable = ['created_by_id', 'requested_by_id', 'company_id', 'name'];

    /**
     *
     */
    public static function boot()
    {
        static::addGlobalScope(new CompanyScope());
    }

    /**
     * @return mixed
     */
    public function createdBy()
    {
        return $this->belongsTo('Insight\Users\User', 'created_by_id');
    }

    /**
     * @return mixed
     */
    public function requestedBy()
    {
        return $this->belongsTo('Insight\Users\User', 'requested_by_id');
    }

    /**
     * @return mixed
     */
    public function company()
    {
        return $this->belongsTo('Insight\Companies\Company');
    }

    /**
     * @return mixed
     */
    public function productRequests()
    {
        return $this->hasMany('Insight\ProductRequests\ProductRequest', 'list_id');
    }
}
