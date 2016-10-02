<?php

namespace Insight\ProductProposals\Commands;

use Insight\ProductProposals\ProductProposal;

/**
 * Class AutomaticProposalApprovalCommand
 * @package Insight\ProductProposals\Commands
 */
class AutomaticProposalApprovalCommand
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
