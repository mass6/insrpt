<?php

namespace Insight\Quotations\Commands;

use Insight\Quotations\QuotationRequest;
use Insight\Users\User;

/**
 * Class UpdateQuotationRequestCommand
 * @package Insight\Quotations\Commands
 */
class UpdateQuotationRequestCommand
{

    /**
     * @var User
     */
    public $user;
    /**
     * @var QuotationRequest
     */
    public $quotationRequest;
    /**
     * @var
     */
    public $supplier_id;
    /**
     * @var
     */
    public $send_to_supplier;
    /**
     * @var
     */
    public $message;
    /**
     * @var
     */
    public $ccSender;
    /**
     * @var
     */
    public $transition;


    /**
     * @param User $user
     * @param QuotationRequest $quotationRequest
     * @param $supplier_id
     * @param $send_to_supplier
     * @param $message
     * @param $ccSender
     * @param $transition
     */
    public function __construct(User $user, QuotationRequest $quotationRequest, $supplier_id,
                                $send_to_supplier, $message, $ccSender, $transition)
    {

        $this->user = $user;
        $this->quotationRequest = $quotationRequest;
        $this->supplier_id = $supplier_id;
        $this->send_to_supplier = $send_to_supplier;
        $this->message = $message;
        $this->ccSender = $ccSender;
        $this->transition = $transition;
    }
}
