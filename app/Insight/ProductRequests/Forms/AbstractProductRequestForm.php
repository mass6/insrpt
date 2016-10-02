<?php

namespace Insight\ProductRequests\Forms;

use Laracasts\Validation\FormValidationException;
use Laracasts\Validation\FormValidator;

/**
 * Class AbstractProductRequestForm
 * @package Insight\ProductRequests\Forms
 */
class AbstractProductRequestForm extends FormValidator
{

    /**
     * Price fields
     *
     * @var array
     */
    protected $prices = [
        'current_price',
    ];

    /**
     * Common Validation Rules
     * @var array
     */
    protected $rules = [
        'product_description'      => 'required|max:140',
        'category'                 => 'max:50',
        'purchase_recurrence'      => 'in:AHC,MON,ANN',
        'uom'                      => 'max:30',
        'first_time_order_quantity'         => 'integer',
        'volume_requested'         => 'integer',
        'sku'                      => 'max:60',
        'current_price'            => 'numeric',
        'current_price_currency'   => 'required_with:current_price|alpha|max:3',
        'current_supplier'         => 'max:140',
        'current_supplier_contact' => 'max:2000',
        'reference1'               => 'max:100',
        'reference2'               => 'max:100',
        'reference3'               => 'max:100',
        'reference4'               => 'max:100',
        'cataloguing_item_code'    => 'max:50',
        'cataloguing_product_name' => 'max:140',
        'remarks'                  => 'max:2000',
    ];

    /**
     * Custom validation error messages
     *
     * @var array
     */
    protected $messages = [
        'reason_closed.required_if'              => 'The Reason Closed field is required when the request status is Closed.',
        'category.required'                      => 'Category is required when submitting request.',
        'uom.required'                           => 'UOM is required when submitting request.',
        'purchase_recurrence.required'           => 'Purchase recurrence is required when submitting request.',
        'purchase_recurrence.in'                 => "Accepted values for Purchase Recurrence are AHC, MON, or ANN.",
        'first_time_order_quantity.integer'               => 'Order Quantity must be a whole number (integer) value.',
        'volume_requested.integer'               => 'Volume must be a whole number (integer) value.',
        'first_time_order_quantity.required'              => 'Order Quantity is required when submitting request.',
        'volume_requested.required'              => 'Recurrence Quantity is required if Purchase Recurrence field value is other than One-time/Ad-hoc.',
        'current_price.numeric'                  => 'Current price must be a numeric value (no spaces, letters or symbols).',
        'current_supplier_contact.max'           => 'Supplier contact details can not exceed 2000 characters.',
        'current_supplier_contact.required_with' => 'Supplier contact details are required if Current Supplier field is populated.',
        'remarks.max'                            => 'Remarks can not exceed 2000 characters.',
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
        $this->addReferenceFieldValidationMessages($formData);
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

    /**
     * @param $formData
     */
    protected function addReferenceFieldValidationMessages($formData)
    {
        for ($r = 1; $r <= 4; $r ++) {
            $this->messages["reference{$r}.required"] = "{$formData["reference{$r}_label"]} is required when submitting request.";
            $this->messages["reference{$r}.max"] = "{$formData["reference{$r}_label"]} can not exceed 100 characters.";
        }
    }


}
