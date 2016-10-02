<?php

namespace Insight\Quotations\Events;

use Insight\Quotations\Quotation;

/**
 * Class QuotationWasReceived
 * @package Insight\Quotations\Events
 */
class QuotationWasReceived
{

    /**
     * @var Quotation
     */
    public $quotationRequest;

    /**
     * @param Quotation $quotation
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }
}
