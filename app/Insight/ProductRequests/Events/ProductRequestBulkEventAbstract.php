<?php

namespace Insight\ProductRequests\Events;

/**
 * Class ProductRequestBulkEventAbstract
 * @package Insight\ProductRequests\Events
 */
class ProductRequestBulkEventAbstract
{

    /**
     * @var array
     */
    public $product_requests;

    /**
     * @param array $product_requests
     */
    public function __construct(array $product_requests)
    {
        $this->product_requests = $product_requests;
    }
}
