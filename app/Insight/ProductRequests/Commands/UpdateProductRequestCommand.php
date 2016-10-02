<?php

namespace Insight\ProductRequests\Commands;

use Insight\ProductRequests\ProductRequest;
use Insight\Users\User;

class UpdateProductRequestCommand
{

    public $productRequest;
    public $user;
    public $product_description;
    public $uom;
    public $category;
    public $purchase_recurrence;
    public $first_time_order_quantity;
    public $volume_requested;
    public $sku;
    public $current_price;
    public $current_price_currency;
    public $current_supplier;
    public $current_supplier_contact;
    public $contracts_assigned;
    public $reference1;
    public $reference2;
    public $reference3;
    public $reference4;
    public $cataloguing_item_code;
    public $cataloguing_product_name;
    public $remarks;
    public $attachments;
    public $transitionName;
    public $reason_closed;


    public function __construct(
        ProductRequest $productRequest, User $user, $product_description, $uom, $category, $purchase_recurrence, $first_time_order_quantity, $volume_requested,
        $sku, $current_price, $current_price_currency, $current_supplier, $current_supplier_contact, $contracts_assigned,
        $reference1, $reference2, $reference3, $reference4, $cataloguing_item_code, $cataloguing_product_name, $remarks, $attachments, $transitionName, $reason_closed
    )
    {
        $this->productRequest = $productRequest;
        $this->user = $user;
        $this->product_description = $product_description;
        $this->uom = $uom;
        $this->category = $category;
        $this->purchase_recurrence = $purchase_recurrence;
        $this->first_time_order_quantity = $first_time_order_quantity;
        $this->volume_requested = $volume_requested;
        $this->sku = $sku;
        $this->current_price = $current_price;
        $this->current_price_currency = $current_price_currency;
        $this->current_supplier = $current_supplier;
        $this->current_supplier_contact = $current_supplier_contact;
        $this->contracts_assigned = $contracts_assigned;
        $this->reference1 = $reference1;
        $this->reference2 = $reference2;
        $this->reference3 = $reference3;
        $this->reference4 = $reference4;
        $this->cataloguing_item_code = $cataloguing_item_code;
        $this->cataloguing_product_name = $cataloguing_product_name;
        $this->remarks = $remarks;
        $this->attachments = $attachments;
        $this->transitionName = $transitionName;
        $this->reason_closed = $reason_closed;
    }

}
