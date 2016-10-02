<?php

namespace Insight\Quotations\Events;

use Insight\Quotations\QuotationRequest;

/**
 * Class QuotationRequestWasSent
 * @package Insight\Quotations\Events
 */
class QuotationRequestWasSent
{

    /**
     * @var QuotationRequest
     */
    public $quotationRequest;

    /**
     * @var bool
     */
    public $ccSender;


    /**
     * @param QuotationRequest $quotationRequest
     * @param bool             $ccSender
     */
    public function __construct(QuotationRequest $quotationRequest, $ccSender = false)
    {
        $this->quotationRequest = $quotationRequest;
        $this->ccSender = $ccSender;
    }
}
