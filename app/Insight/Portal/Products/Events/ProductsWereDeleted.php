<?php namespace Insight\Portal\Products\Events;
/**
 * Insight Client Management Portal:
 * Date: 8/14/14
 * Time: 2:23 PM
 */

class ProductsWereDeleted
{
    public $products;

    public function __construct($products)
    {
        $this->products = $products;
    }
} 