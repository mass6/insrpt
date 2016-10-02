<?php

namespace Insight\ProductProposals\Forms;

use Illuminate\Support\Facades\Request;
use Laracasts\Validation\FormValidationException;
use Laracasts\Validation\FormValidator;

/**
 * Class AbstractProductRequestForm
 * @package Insight\ProductProposals\Forms
 */
class AbstractProductProposalForm extends FormValidator
{

    /**
     * Price fields
     *
     * @var array
     */
    protected $prices = [
        'price',
    ];

    /**
     * Common Validation Rules
     * @var array
     */
    protected $rules = [
        'product_name'        => 'required|max:140',
        'product_description' => 'max:2000',
        'sku'                 => 'max:60',
        'uom'                 => 'max:30',
        'volume'              => 'integer',
        'price'               => 'numeric',
        'price_currency'      => 'required_with:price|alpha|max:3',
        'remarks'             => 'max:2000',
    ];

    /**
     * Custom validation error messages
     *
     * @var array
     */
    protected $messages = [
        'uom.required'    => 'UOM is required when submitting the proposal.',
        'volume.integer'  => 'Volume must be a whole number (integer) value.',
        'volume.required' => 'Volume is required when submitting request.',
        'price.required'  => 'Price is required when submitting the proposal.',
        'price.numeric'   => 'Price must be a numeric value (no spaces, letters or symbols).',
        'remarks.max'     => 'Remarks can not exceed 2000 characters.',
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
            if (!Request::ajax()) {
                throw new FormValidationException('Validation failed', $this->getValidationErrors());
            } else {
                $errors = [];
                foreach ($this->validation->messages()->toArray() as $field => $messages) {
                    $errors[] = ['name' => $field, 'status' => implode('<br/>', $messages)];
                }

                return ['result' => 'failed', 'errors' => $errors];
            }
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
