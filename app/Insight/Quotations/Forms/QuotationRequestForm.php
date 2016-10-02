<?php

namespace Insight\Quotations\Forms;

use Insight\Quotations\Exceptions\QuotationRequestFormException;
use Laracasts\Validation\FormValidator;

/**
 * Class QuotationRequestForm
 * @package Insight\QuotationRequests\Forms
 */
class QuotationRequestForm extends FormValidator
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
        'supplier_id'      => 'exists:suppliers,id',
        'send_to_supplier' => 'boolean',
        'message'          => 'required_with:send_to_supplier|max:1000',
    ];

    /**
     * Custom validation error messages
     *
     * @var array
     */
    protected $messages = [
        'supplier_id.required' => 'You must select a supplier.',
        'message.required_with' => 'You must include a message when emailing a Quotation Request to a supplier.'
    ];

    /**
     * Validate the form data
     *
     * @param array $formData
     * @return mixed
     * @throws QuotationRequestFormException
     */
    public function validate(array $formData)
    {
        $this->validation = $this->validator->make(
            $formData,
            $this->compileValidationRules($formData),
            $this->getValidationMessages()
        );

        if ($this->validation->fails()) {
            throw new QuotationRequestFormException('Validation failed', $this->getValidationErrors());
        }

        return true;
    }

    private function compileValidationRules($formData)
    {
        $transition = key($formData['transition']);
        if ($transition === 'submit') {
            $this->rules['supplier_id'] = 'required|exists:suppliers,id';
        }

        return $this->getValidationRules();
    }


}
