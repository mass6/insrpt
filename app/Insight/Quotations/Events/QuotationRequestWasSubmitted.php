<?php

namespace Insight\Quotations\Events;

use Insight\Quotations\QuotationRequest;

/**
 * Class QuotationRequestWasSubmitted
 * @package Insight\Quotations\Events
 */
class QuotationRequestWasSubmitted
{

    /**
     * @var QuotationRequest
     */
    public $quotationRequest;

    /**
     * @param QuotationRequest $quotationRequest
     */
    public function __construct(QuotationRequest $quotationRequest)
    {
        $this->quotationRequest = $quotationRequest;
    }
}
