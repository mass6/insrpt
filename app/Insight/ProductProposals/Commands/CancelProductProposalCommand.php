<?php

namespace Insight\ProductProposals\Commands;

use Insight\ProductProposals\ProductProposal;
use Insight\Users\User;

/**
 * Class CancelProductProposalCommand
 * @package Insight\ProductProposals\Commands
 */
class CancelProductProposalCommand
{

    /**
     * @var ProductProposal
     */
    public $productProposal;

    /**
     * @var User
     */
    public $user;


    /**
     * CancelProposalApprovalCommand constructor.
     *
     * @param ProductProposal $productProposal
     * @param User            $user
     */
    public function __construct(ProductProposal $productProposal, User $user)
    {
        $this->productProposal = $productProposal;
        $this->user            = $user;
    }
}
