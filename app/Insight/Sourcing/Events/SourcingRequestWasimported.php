<?php namespace Insight\Sourcing\Events;

/**
 * Class ProductDefinition
 * @package Insight\ProductDefinitions\Events
 */
class SourcingRequestWasImported
{

    /**
     * @var array
     */
    public $sourcingRequests;

    /**
     * @param array $sourcingRequests
     */
    public function __construct(Array $sourcingRequests)
    {
        $this->sourcingRequests = $sourcingRequests;
    }

}