<?php

namespace Insight\Sourcing\Forms;

use Laracasts\Validation\FormValidationException;
use Laracasts\Validation\FormValidator;

class ImportSourcingRequestForm extends FormValidator
{

    protected $rules = [
        'customer_id' => 'required|exists:companies,id',
        'received_on' => 'date',
        'batch'       => 'max:120',
        'importfile'  => 'required|mimes:xls,xlsx,csv|max:4096'
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
        $this->validation = $this->validator->make(
            $formData,
            $this->getValidationRules(),
            $this->getValidationMessages()
        );

        if ($this->validation->fails()) {
            throw new FormValidationException('Validation failed', $this->getValidationErrors());
        }

        return true;
    }

}
 