<?php namespace Insight\Sourcing\Events;

use Insight\Sourcing\SourcingRequest;


/**
 * Class ProductDefinition
 * @package Insight\ProductDefinitions\Events
 */
class SourcingRequestWasCreated
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