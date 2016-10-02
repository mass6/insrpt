<?php

namespace Insight\ProductRequests\Events;

use Insight\ProductRequests\ProductRequest;

/**
 * Class ProductRequestWasUpdated
 * @package Insight\ProductRequests\Events
 */
class ProductRequestWasUpdated
{

    /**
     * @var ProductRequest
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
 