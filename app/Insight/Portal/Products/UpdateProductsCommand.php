<?php namespace Insight\Portal\Products;
/**
 * Insight Client Management Portal:
 * Date: 8/10/14
 * Time: 3:22 PM
 */
use Insight\Companies\Company;

/**
 * Class UpdateProductsCommand
 * @package Insight\Portal\Products
 */
class UpdateProductsCommand
{
    /**
     * @var array
     */
    public $localProducts;

    /**
     * @var array
     */
    public $portalProducts;

    /**
     * @var array
     */
    public $customer;

    /**
     * @var
     */
    public $website;


    /**
     * @param array   $localProducts
     * @param array   $portalProducts
     * @param Company $customer
     * @param         $website
     */
    public function __construct(Array $localProducts, Array $portalProducts, Company $customer, $website)
    {
        $this->localProducts = $localProducts;
        $this->portalProducts = $portalProducts;
        $this->customer = $customer;
        $this->website = $website;
    }
    
} 