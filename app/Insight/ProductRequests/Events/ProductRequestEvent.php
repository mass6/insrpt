<?php

namespace Insight\ProductRequests\Events;

use Insight\ProductRequests\ProductRequest;

/**
 * Class ProductRequestEvent
 * @package Insight\ProductRequests\Events
 */
abstract class ProductRequestEvent
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
