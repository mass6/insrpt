<?php

namespace Insight\Quotations\Forms;

use Laracasts\Validation\FormValidator;

/**
 * Class QuotationForm
 * @package Insight\Quotations\Forms
 */
class QuotationForm extends FormValidator
{

    /**
     * Price fields
     *
     * @var array
     */
    protected $prices = [
        'current_price', 'unit_price', 'total_price'
    ];

    /**
     * Common Validation Rules
     * @var array
     */
    protected $rules = [
        'product_description'           => 'max:140',
        'supplier_id'                   => 'exists:suppliers,id',
        'request_id'                    => 'exists:product_requests,request_id',
        'uom'                           => 'max:30',
        'volume'                        => 'integer',
        'current_price'                 => 'numeric',
        'current_price_currency'        => 'alpha|size:3',
        'quotation_date'                => 'date',
        'supplier_reference'            => 'max:50',
        'suppliers_product_name'        => 'max:140',
        'suppliers_product_description' => 'max:2000',
        'suppliers_product_sku'         => 'max:50',
        'suppliers_uom'                 => 'max:30',
        'suppliers_quantity'            => 'integer',
        'unit_price'                    => 'numeric',
        'price_currency'                => 'alpha|size:3',
        'total_price'                   => 'numeric',
        'valid_until'                   => 'date',
        'delivery_terms'                => 'max:1000',
        'payment_terms'                 => 'max:1000',
    ];


    /**
     * Custom validation error messages
     *
     * @var array
     */
    protected $messages = [
//        'supplier_id.required'  => 'You must select a supplier.',
//        'message.required_with' => 'You must include a message when emailing a Quotation Request to a supplier.'
    ];

    /**
     * Validate the form data
     *
     * @param array $formData
     * @return mixed
     */
    public function validate(array $formData)
    {
        $this->validation = $this->validator->make(
            $formData,
            $this->compileValidationRules($formData),
            $this->getValidationMessages()
        );

        if ($this->validation->fails()) {
            $errors = [];
            foreach ($this->validation->messages()->toArray() as $field => $messages) {
                $errors[] = ['name' => $field, 'status' => implode('<br/>', $messages)];
            }

            return ['result' => 'failed', 'errors' => $errors];
        }

        return ['result' => 'passed'];
    }

    /**
     * @param $formData
     * @return array
     */
    private function compileValidationRules($formData)
    {

        if (isset($formData['actionCreate'])) {
            $this->rules['suppliers_product_name'] = 'required|max:140';
        }

        return $this->getValidationRules();
    }


}
