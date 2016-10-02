<?php

namespace Insight\ProductProposals\Events;

abstract class ProductProposalBulkEventAbstract
{
    /**
     * @var array
     */
    public $productProposals;

    /**
     * @param array $productProposals
     */
    public function __construct(array $productProposals)
    {
        $this->productProposals = $productProposals;
    }

}
