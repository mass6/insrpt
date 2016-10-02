<?php

namespace Insight\Quotations\Events;

use Insight\Quotations\QuotationRequest;

class QuotationRequestWasCreated
{

    /**
     * @var QuotationRequest
     */
    public $quotationRequest;

    public function __construct(QuotationRequest $quotationRequest)
    {
        $this->quotationRequest = $quotationRequest;
    }
}
