<?php

namespace Insight\ProductProposals\Events;

use Insight\ProductProposals\ProductProposal;

/**
 * Class ProductProposalWasAssigned
 * @package Insight\ProductProposals\Events
 */
class ProductProposalWasAssigned
{

    /**
     * @var ProductProposal
     */
    public $productProposal;

    /**
     * @param ProductProposal $productProposal
     */
    public function __construct(ProductProposal $productProposal)
    {
        $this->productProposal = $productProposal;
    }
}