<?php

namespace Insight\Sourcing\Events;

use Insight\Sourcing\SourcingRequest;

/**
 * Class SourcingRequestWasAssigned
 * @package Insight\Sourcing\Events
 */
class SourcingRequestWasAssigned
{

    /**
     * @var SourcingRequest
     */
    public $sourcingRequest;

    /**
     * @param SourcingRequest $sourcingRequest
     */
    public function __construct(SourcingRequest $sourcingRequest)
    {
        $this->sourcingRequest = $sourcingRequest;
    }

}