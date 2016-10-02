<?php

namespace Insight\Companies;

/**
 * Class Supplier
 * @package Insight
 */
/**
 * Class Supplier
 * @package Insight\Companies
 */
class Supplier extends \Eloquent
{

    /**
     * @var array
     */
    protected $fillable = ['name', 'address', 'email', 'website', 'primary_contact', 'telephone1',
        'telephone2', 'fax', 'description'
    ];

    /**
     * @return mixed
     */
    public function quotations()
    {
        return $this->hasMany('Insight\Quotations\Quotation');
    }
}
