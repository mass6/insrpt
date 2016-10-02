<?php namespace Insight\Sourcing\Forms;

/**
 * Insight Client Management Portal:
 * Date: 11/7/14
 * Time: 1:00 PM
 */
class UpdateSourcingRequestForm extends AbstractSourcingRequestForm
{

    /**
     * @param array $formData
     * @return array
     */
    protected function compileRules(array $formData)
    {
        $this->rules['customer_sku'] = 'max:120|unique:sourcing_requests,customer_sku,' . $formData['id'] . ',id,customer_id,' . $formData['customer_id'];

        return $this->rules;
    }

}