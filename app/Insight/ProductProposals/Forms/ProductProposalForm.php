<?php

namespace Insight\ProductProposals\Forms;

/**
 * Class SubmitProductRequestForm
 * @package Insight\ProductProposals\Forms
 */
class ProductProposalForm extends AbstractProductProposalForm
{

    /**
     * @param array $formData
     * @return array
     */
    protected function compileRules(array $formData)
    {
        if ($formData['transition'] !== 'save_draft') {
            $this->addRequiredFields($formData);
        }


        return $this->rules;
    }

    /**
     * @param $formData
     */
    protected function addRequiredFields($formData)
    {
        $this->rules['uom'] = 'required|max:30';
        $this->rules['volume'] = 'required|integer';
        $this->rules['price'] = 'required|numeric';
    }


}
