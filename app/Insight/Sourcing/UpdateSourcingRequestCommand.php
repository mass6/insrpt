<?php namespace Insight\Sourcing;

use Insight\Users\User;

/**
 * Class UpdateSourcingRequestCommand
 * @package Insight\Sourcing
 */
class UpdateSourcingRequestCommand
{

    /**
     * @var SourcingRequest
     */
    public $sourcingRequest;
    /**
     * @var User
     */
    public $user;
    /**
     * @var
     */
    public $batch;
    /**
     * @var
     */
    public $received_on;
    /**
     * @var
     */
    public $customer_sku;
    /**
     * @var
     */
    public $customer_product_description;
    /**
     * @var
     */
    public $customer_price;
    /**
     * @var
     */
    public $customer_price_currency;
    /**
     * @var
     */
    public $customer_uom;
    /**
     * @var
     */
    public $tss_sku;
    /**
     * @var
     */
    public $tss_product_name;
    /**
     * @var
     */
    public $tss_buy_price;
    /**
     * @var
     */
    public $tss_buy_price_currency;
    /**
     * @var
     */
    public $tss_uom;
    /**
     * @var
     */
    public $supplier_name;
    /**
     * @var
     */
    public $tss_sell_price;
    /**
     * @var
     */
    public $tss_sell_price_currency;
    /**
     * @var
     */
    public $assigned_to_id;
    /**
     * @var
     */
    public $status;
    /**
     * @var
     */
    public $reason_closed;
    /**
     * @var
     */
    public $remarks;

    /**
     * @param SourcingRequest $sourcingRequest
     * @param User $user
     * @param $batch
     * @param $received_on
     * @param $customer_sku
     * @param $customer_product_description
     * @param $customer_price
     * @param $customer_price_currency
     * @param $customer_uom
     * @param $tss_sku
     * @param $tss_product_name
     * @param $tss_buy_price
     * @param $tss_buy_price_currency
     * @param $tss_uom
     * @param $supplier_name
     * @param $tss_sell_price
     * @param $tss_sell_price_currency
     * @param $assigned_to_id
     * @param $status
     * @param $reason_closed
     * @param $remarks
     */
    public function __construct(
        SourcingRequest $sourcingRequest, User $user, $batch, $received_on, $customer_sku,
        $customer_product_description, $customer_price, $customer_price_currency,
        $customer_uom, $tss_sku, $tss_product_name, $tss_buy_price,
        $tss_buy_price_currency, $tss_uom, $supplier_name, $tss_sell_price,
        $tss_sell_price_currency, $assigned_to_id, $status, $reason_closed, $remarks)
    {
        $this->sourcingRequest = $sourcingRequest;
        $this->user = $user;
        $this->batch = $batch;
        $this->received_on = $received_on;
        $this->customer_sku = $customer_sku;
        $this->customer_product_description = $customer_product_description;
        $this->customer_price = $customer_price;
        $this->customer_price_currency = $customer_price_currency;
        $this->customer_uom = $customer_uom;
        $this->tss_sku = $tss_sku;
        $this->tss_product_name = $tss_product_name;
        $this->tss_buy_price = $tss_buy_price;
        $this->tss_buy_price_currency = $tss_buy_price_currency;
        $this->tss_uom = $tss_uom;
        $this->supplier_name = $supplier_name;
        $this->tss_sell_price = $tss_sell_price;
        $this->tss_sell_price_currency = $tss_sell_price_currency;
        $this->assigned_to_id = $assigned_to_id;
        $this->status = $status;
        $this->reason_closed = $reason_closed;
        $this->remarks = $remarks;
    }
}