<?php

namespace Insight\ProductRequests\Forms;

/**
 * Class ProductRequestValidationRule
 * @package Insight\ProductRequests\Forms
 */
class ProductRequestValidationRule
{

    /**
     * @var SubmitProductRequestForm
     */
    private $submitProductRequestForm;

    /**
     * @param SubmitProductRequestForm $submitProductRequestForm
     */
    public function __construct(SubmitProductRequestForm $submitProductRequestForm)
    {

        $this->submitProductRequestForm = $submitProductRequestForm;
    }

    /**
     * Return the standard validation rules for creating a Product Request
     *
     * @return array
     */
    public function getValidationRules()
    {
        return $this->submitProductRequestForm->getValidationRules();
    }
}
 