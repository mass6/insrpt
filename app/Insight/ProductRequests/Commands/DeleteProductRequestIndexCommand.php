<?php

namespace Insight\ProductRequests\Commands;

use Insight\ProductRequests\ProductRequest;

class DeleteProductRequestIndexCommand
{

    /**
     * @var ProductRequest
     */
    public $productRequest;


    public function __construct(ProductRequest $productRequest)
        {
            $this->productRequest = $productRequest;
        }
}
