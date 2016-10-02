<?php namespace Insight\Sourcing;

class AddNewSourcingRequestCommand
{

    public $user;
    public $customer_id;
    public $batch;
    public $received_on;
    public $customer_sku;
    public $customer_product_description;
    public $customer_price;
    public $customer_price_currency;
    public $customer_uom;
    public $tss_sku;
    public $tss_product_name;
    public $tss_buy_price;
    public $tss_buy_price_currency;
    public $tss_uom;
    public $supplier_name;
    public $tss_sell_price;
    public $tss_sell_price_currency;
    public $assigned_to_id;
    public $status;
    public $remarks;

    public function __construct(
        $user, $customer_id, $batch, $received_on, $customer_sku,
        $customer_product_description, $customer_price, $customer_price_currency,
        $customer_uom, $tss_sku, $tss_product_name, $tss_buy_price,
        $tss_buy_price_currency, $tss_uom, $supplier_name, $tss_sell_price,
        $tss_sell_price_currency, $assigned_to_id, $status, $remarks)
    {
        $this->user = $user;
        $this->customer_id = $customer_id;
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
        $this->remarks = $remarks;
    }
}