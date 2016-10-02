<?php

namespace Insight\ProductRequests\Events;

use Insight\ProductRequests\ProductRequest;

/**
 * Class ProductRequestWasCompleted
 * @package Insight\ProductRequests\Events
 */
class ProductRequestWasCompleted
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
