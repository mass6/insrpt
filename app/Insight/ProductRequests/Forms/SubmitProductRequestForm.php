<?php

namespace Insight\ProductRequests\Forms;

/**
 * Class SubmitProductRequestForm
 * @package Insight\ProductRequests\Forms
 */
class SubmitProductRequestForm extends AbstractProductRequestForm
{

    /**
     * Common Validation Rules
     * @var array
     */
    protected $rules = [
        'product_description'      => 'required|max:140',
        'category'                 => 'required|max:50',
        'purchase_recurrence'      => 'required|in:AHC,MON,ANN',
        'uom'                      => 'required|max:30',
        'first_time_order_quantity'         => 'required|integer|min:1',
        'volume_requested'         => 'integer',
        'sku'                      => 'max:60',
        'current_price'            => 'numeric',
        'current_price_currency'   => 'required_with:current_price|alpha|max:3',
        'current_supplier'         => 'max:140',
        'current_supplier_contact' => 'required_with:current_supplier|max:2000',
        'reference1'               => 'max:100',
        'reference2'               => 'max:100',
        'reference3'               => 'max:100',
        'reference4'               => 'max:100',
        'cataloguing_item_code'    => 'max:50',
        'cataloguing_product_name' => 'max:140',
        'remarks'                  => 'max:2000',
        'reason_closed'            => 'required_if:transition,close',
    ];

    /**
     * @param array $formData
     * @return array
     */
    protected function compileRules(array $formData)
    {
        if ($formData['purchase_recurrence'] !== 'AHC') { // purchase recurrence is not adhoc
            $this->rules["volume_requested"] = 'required|integer|min:1';
        }
        $this->addReferenceFieldValidationRules($formData);

        return $this->rules;
    }

    /**
     * @param $formData
     */
    protected function addReferenceFieldValidationRules($formData)
    {
        for ($r = 1; $r <= 4; $r ++) {
            if ((bool) $formData["reference{$r}_enabled"] && (bool) $formData["reference{$r}_required"]) {
                $this->rules["reference{$r}"] = 'required|max:100';
            }
        }
    }


}
