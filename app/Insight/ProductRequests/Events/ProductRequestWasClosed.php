<?php

namespace Insight\ProductRequests\Events;

use Insight\ProductRequests\ProductRequest;

/**
 * Class ProductRequestWasClosed
 * @package Insight\ProductRequests\Events
 */
class ProductRequestWasClosed
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
