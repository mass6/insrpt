<?php namespace Insight\Sourcing\Forms;

/**
 * Insight Client Management Portal:
 * Date: 11/7/14
 * Time: 1:00 PM
 */
class NewSourcingRequestForm extends AbstractSourcingRequestForm
{

    /**
     * @param array $formData
     * @return array
     */
    protected function compileRules(array $formData)
    {
        $this->rules['customer_id'] = 'required|integer|exists:companies,id';

        // Ensure the sku is unique per customer
        $this->rules['customer_sku'] = 'max:120|unique:sourcing_requests,customer_sku,NULL,id,customer_id,' . $formData['customer_id'];

        return $this->rules;
    }
} 