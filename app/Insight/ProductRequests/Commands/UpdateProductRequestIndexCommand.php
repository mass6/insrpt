<?php

namespace Insight\ProductRequests\Commands;

class UpdateProductRequestIndexCommand
{

    public $productRequests;


    public function __construct($productRequests)
        {
            $this->productRequests = $productRequests;
        }
}
