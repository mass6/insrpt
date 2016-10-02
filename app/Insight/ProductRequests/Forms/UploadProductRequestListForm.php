<?php

namespace Insight\ProductRequests\Forms;

use Laracasts\Validation\FormValidationException;
use Laracasts\Validation\FormValidator;

/**
 * Class UploadProductRequestListForm
 * @package Insight\ProductRequests\Forms
 */
class UploadProductRequestListForm extends FormValidator
{

    /**
     * @var array
     */
    protected $rules = [
        'name'       => 'required|max:60',
        'uploadfile' => 'required|mimes:xls,xlsx,csv|max:4096'
    ];

    /**
     * @var array
     */
    protected $messages = [
        'name.unique' => "List name already in use. List names must be unique.",
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
            $this->compileValidationRules($formData),
            $this->getValidationMessages()
        );

        if ($this->validation->fails()) {
            throw new FormValidationException('Validation failed', $this->getValidationErrors());
        }

        return true;
    }

    /**
     * @param $formData
     * @return array
     */
    private function compileValidationRules($formData)
    {
        $this->rules['name'] = 'required|max:60|unique:product_request_lists,name,NULL,id,company_id,' . $formData['company_id'];

        return $this->rules;
    }

}
 