<?php

namespace Insight\Quotations\Events;

use Insight\Quotations\QuotationRequest;

/**
 * Class QuotationRequestWasUpdated
 * @package Insight\Quotations\Events
 */
class QuotationRequestWasUpdated
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
