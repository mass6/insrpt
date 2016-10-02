<?php

namespace Insight\Sourcing\Forms;

use Laracasts\Validation\FormValidationException;
use Laracasts\Validation\FormValidator;

abstract class AbstractSourcingRequestForm extends FormValidator
{

    /**
     * Price fields
     *
     * @var array
     */
    protected $prices = [
        'customer_price',
        'tss_buy_price',
        'tss_sell_price',
    ];

    /**
     * Common Validation Rules
     * @var array
     */
    protected $rules = [

        'batch'                        => 'max:120',
        'received_on'                  => 'required|date',

        'customer_product_description' => 'required|max:256',
        'customer_price'               => 'numeric',
        'customer_price_currency'      => 'required_with:customer_price|alpha|max:3',
        'customer_uom'                 => 'max:256',

        'tss_sku'                      => 'max:120',
        'tss_product_name'             => 'max:256',
        'tss_buy_price'                => 'numeric',
        'tss_buy_price_currency'       => 'required_with:tss_buy_price|alpha|max:3',
        'tss_uom'                      => 'max:256',
        'supplier_name'                => 'max:256',
        'tss_sell_price'               => 'numeric',
        'tss_sell_price_currency'      => 'required_with:tss_sell_price|alpha|max:3',

        'assigned_to_id'               => 'integer',
        'status'                       => 'required',
        'reason_closed'                => 'required_if:status,CLS',
        'remarks'                      => 'max:1000',
    ];

    /**
     * Custom validation error messages
     *
     * @var array
     */
    protected $messages = [
        'customer_sku.unique'                => 'Customer SKU number must be unique.',
        'reason_closed.required_if'          => 'The Reason Closed field is required when the request status is Closed.',
        'customer_price.numeric'             => 'The customer price must be a numeric value (no spaces, letters or symbols).',
        'tss_buy_price.numeric'              => 'The 36S buy price must be a numeric value (no spaces, letters or symbols).',
        'tss_sell_price.numeric'             => 'The 36S sell price must be a numeric value (no spaces, letters or symbols).',
        'remarks.max'                        => 'Remarks can not exceed 1000 characters.',
    ];

    /**
     * Validate the form data
     *
     * @param array $formData
     * @return mixed
     * @throws FormValidationException
     */
    public function validate(array $formData)
    {
        $formData = $this->removeCommasFromPriceFields($formData);

        $this->validation = $this->validator->make(
            $formData,
            $this->compileRules($formData),
            $this->getValidationMessages()
        );

        if ($this->validation->fails()) {
            throw new FormValidationException('Validation failed', $this->getValidationErrors());
        }

        return true;
    }

    /**
     * Remove commas from price fields
     *
     * @param $formData
     * @return mixed
     */
    private function removeCommasFromPriceFields($formData)
    {
        $priceFields = array_only($formData, $this->prices);

        return array_merge($formData, removeCommasFromPriceFields($priceFields));
    }


}
 