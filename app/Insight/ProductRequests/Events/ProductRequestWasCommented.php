<?php

namespace Insight\ProductRequests\Events;

use Insight\ProductRequests\ProductRequest;

/**
 * Class ProductRequestWasCommented
 * @package Insight\ProductRequests\Events
 */
class ProductRequestWasCommented
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
 