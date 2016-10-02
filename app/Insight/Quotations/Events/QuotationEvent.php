<?php

namespace Insight\Quotations\Events;

use Insight\Quotations\Quotation;

/**
 * Class QuotationEvent
 * @package Insight\Quotations\Events
 */
class QuotationEvent
{

    /**
     * @var Quotation
     */
    public $quotation;

    /**
     * @param Quotation $quotation
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }
}
