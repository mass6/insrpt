<?php

namespace Insight\ProductProposals\Commands;

use Insight\ProductProposals\ProductProposal;
use Insight\Users\User;

/**
 * Class ProductProposalApprovalCommand
 * @package Insight\ProductProposals\Commands
 */
class ProductProposalApprovalCommand
{

    /**
     * @var ProductProposal
     */
    public $productProposal;
    /**
     * @var
     */
    public $transition;
    /**
     * @var User
     */
    public $user;
    /**
     * @var
     */
    public $remarks;
    /**
     * @var
     */
    public $reject_reason;


    /**
     * @param User            $user
     * @param ProductProposal $productProposal
     * @param                 $transition
     * @param                 $remarks
     * @param                 $reject_reason
     */
    public function __construct(User $user, ProductProposal $productProposal, $transition, $remarks, $reject_reason)
    {
        $this->user = $user;
        $this->productProposal = $productProposal;
        $this->transition = $transition;
        $this->remarks = $remarks;
        $this->reject_reason = $reject_reason;
    }
}
