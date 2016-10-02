<?php 

namespace Insight\Sourcing\Events;

use Insight\Sourcing\SourcingRequest;

class SourcingRequestWasCommented
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
 