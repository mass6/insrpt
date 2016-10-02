<?php

namespace Insight\Companies;

use Laracasts\Commander\Events\EventGenerator;

/**
 * Class Company
 * @package Insight\Companies
 */
class Company extends \Eloquent
{

    use EventGenerator;

    /**
     * @var array
     */
    protected $fillable = ['name', 'type', 'notes', 'primary_contact_user_id', 'magento_customer_group_id', 'settings'];

    /**
     * @var string
     */
    protected $table = 'companies';

    public function groupId()
    {
        return $this->attribute['magento_group_id'];
    }

    public function websiteCode()
    {
        return $this->settings()->get('portal.website_code');
    }

    /**
     * @return mixed
     */
    public function users()
    {
        return $this->hasMany('Insight\Users\User');
    }

    /**
     * @return mixed
     */
    public function sourcingRequests()
    {
        return $this->hasMany('Insight\Sourcing\SourcingRequest', 'customer_id');
    }

    /**
     * @return mixed
     */
    public function productDefinitionsOwned()
    {
        return $this->hasMany('Insight\ProductDefinitions\ProductDefinition', 'company_id');
    }

    /**
     * @return mixed
     */
    public function ProductDefinitionsAssigned()
    {
        return $this->hasMany('Insight\ProductDefinitions\ProductDefinition', 'supplier_id');
    }

    /**
     * @return mixed
     */
    public function suppliers()
    {
        return $this->belongsToMany('Insight\Companies\Company', 'customer_supplier', 'customer_id', 'supplier_id');
    }

    /**
     * @return mixed
     */
    public function associatedSuppliers()
    {
        return $this->hasMany('Insight\Companies\Supplier');
    }

    /**
     * @return mixed
     */
    public function customers()
    {
        return $this->belongsToMany('Insight\Companies\Company', 'customer_supplier', 'supplier_id', 'customer_id');
    }

    /**
     * @return mixed
     */
    public function primaryContact()
    {
        return $this->hasOne('Insight\Users\User', 'id', 'primary_contact_user_id');
    }

    /**
     * @return mixed
     */
    public function attributeSets()
    {
        return $this->hasMany('Insight\ProductDefinitions\AttributeSet');
    }

    /**
     * Return a Settings Class object
     *
     * @return Settings
     */
    public function settings()
    {
        return new CompanySettings(json_decode($this->attributes['settings'], true), $this);
    }

    /**
     * @return mixed
     */
    public function quotations()
    {
        return $this->hasMany('Insight\Quotations\Quotation');
    }

}

