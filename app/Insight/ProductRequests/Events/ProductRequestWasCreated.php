<?php

namespace Insight\ProductRequests\Events;
use Insight\ProductRequests\ProductRequest;


/**
 * Class ProductRequestWasCreated
 * @package Insight\ProductRequests\Events
 */
class ProductRequestWasCreated
{

    /**
     * @var SourcingRequest
     */
    public $productRequest;

    /**
     * @param ProductRequest $productRequest
     */
    public function __construct(ProductRequest $productRequest)
    {
        $this->productRequest = $productRequest;
    }

}