<?php

namespace Insight\ProductProposals\Commands;

use Insight\ProductProposals\ProductProposal;

/**
 * Class AssignProductProposalCommand
 * @package Insight\ProductProposals\Commands
 */
class AssignProductProposalCommand
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
