<?php

namespace Insight\ProductProposals\Commands;

use Insight\ProductRequests\ProductRequest;
use Insight\Users\User;

/**
 * Class AttachNewProductProposalCommand
 * @package Insight\ProductProposals\Commands
 */
class AttachNewProductProposalCommand
{

    /**
     * @var User
     */
    public $user;
    /**
     * @var ProductRequest
     */
    public $productRequest;
    /**
     * @var
     */
    public $quotation_id;
    /**
     * @var
     */
    public $company_id;
    /**
     * @var
     */
    public $product_name;
    /**
     * @var
     */
    public $product_description;
    /**
     * @var
     */
    public $uom;
    /**
     * @var
     */
    public $volume;
    /**
     * @var
     */
    public $sku;
    /**
     * @var
     */
    public $price;
    /**
     * @var
     */
    public $price_currency;
    /**
     * @var
     */
    public $display_quotation_details;
    /**
     * @var
     */
    public $remarks;
    /**
     * @var
     */
    public $transition;
    /**
     * @var
     */
    public $attachments;


    /**
     * @param User $user
     * @param ProductRequest $productRequest
     * @param $quotation_id
     * @param $company_id
     * @param $product_name
     * @param $product_description
     * @param $uom
     * @param $volume
     * @param $sku
     * @param $price
     * @param $price_currency
     * @param $display_quotation_details
     * @param $remarks
     * @param $transition
     * @param $attachments
     */
    public function __construct(User $user, ProductRequest $productRequest, $quotation_id, $company_id, $product_name, $product_description,
                                $uom, $volume, $sku, $price, $price_currency, $display_quotation_details, $remarks, $transition, $attachments)
    {
        $this->user = $user;
        $this->productRequest = $productRequest;
        $this->quotation_id = $quotation_id;
        $this->company_id = $company_id;
        $this->product_name = $product_name;
        $this->product_description = $product_description;
        $this->uom = $uom;
        $this->volume = $volume;
        $this->sku = $sku;
        $this->price = $price;
        $this->price_currency = $price_currency;
        $this->display_quotation_details = $display_quotation_details;
        $this->remarks = $remarks;
        $this->transition = $transition;
        $this->attachments = $attachments;
    }
}
