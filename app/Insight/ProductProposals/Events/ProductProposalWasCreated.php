<?php

namespace Insight\ProductProposals\Events;
use Insight\ProductProposals\ProductProposal;

/**
 * Class ProductProposalWasCreated
 * @package Insight\ProductProposals\Events
 */
class ProductProposalWasCreated
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