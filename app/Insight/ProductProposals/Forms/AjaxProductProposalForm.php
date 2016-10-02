<?php

namespace Insight\ProductProposals\Forms;

/**
 * Class AjaxProductProposalForm
 * @package Insight\ProductProposals\Forms
 */
class AjaxProductProposalForm extends AbstractProductProposalForm
{

    /**
     * @param array $formData
     * @return array
     */
    protected function compileRules(array $formData)
    {
        $this->rules['product_name'] = 'max:140';
        $this->rules['price_currency'] = 'alpha|max:3';
        return $this->rules;
    }

    /**
     * @param $formData
     */
    protected function addRequiredFields($formData)
    {
//        $this->rules['uom'] = 'required|max:30';
    }


}
