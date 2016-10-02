<?php

namespace Insight\ProductProposals\Commands;

use Insight\Users\User;

/**
 * Class ApplyProductProposalBulkTransitionsCommand
 * @package Insight\ProductProposals\Commands
 */
class ApplyProductProposalBulkTransitionsCommand
{

    /**
     * @var
     */
    public $proposalIds;
    /**
     * @var
     */
    public $transition;
    /**
     * @var User
     */
    public $user;

    /**
     * @param $proposalIds
     * @param $transition
     * @param User $user
     */
    public function __construct($proposalIds, $transition, User $user)
    {
        $this->proposalIds = $proposalIds;
        $this->transition = $transition;
        $this->user = $user;
    }
}
