<?php

namespace Insight\ProductProposals\Events;
use Insight\ProductProposals\ProductProposal;

/**
 * Class ProductProposalEvent
 * @package Insight\ProductProposals\Events
 */
abstract class ProductProposalEvent
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