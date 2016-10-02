<?php

namespace Insight\ProductRequests\Commands;

use Insight\Users\User;

class ApplyProductRequestBulkTransitionsCommand
{
    /**
     * @var
     */
    public $requestIds;
    /**
     * @var
     */
    public $transition;
    /**
     * @var User
     */
    public $user;

    /**
     * @param $requestIds
     * @param $transition
     * @param User $user
     */
    public function __construct($requestIds, $transition, User $user)
    {
        $this->requestIds = $requestIds;
        $this->transition = $transition;
        $this->user = $user;
    }
}
