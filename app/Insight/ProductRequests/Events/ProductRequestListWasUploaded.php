<?php

namespace Insight\ProductRequests\Events;

use Insight\ProductRequests\ProductRequestList;


/**
 * Class ProductRequestWasCreated
 * @package Insight\ProductRequests\Events
 */
class ProductRequestListWasUploaded
{

    /**
     * @var ProductRequestList
     */
    public $productRequestList;

    /**
     * @param ProductRequestList $productRequestList
     */
    public function __construct(ProductRequestList $productRequestList)
    {
        $this->productRequestList = $productRequestList;
    }

}