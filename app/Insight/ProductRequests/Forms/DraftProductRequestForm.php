<?php

namespace Insight\ProductRequests\Forms;

class DraftProductRequestForm extends AbstractProductRequestForm
{

    /**
     * @param array $formData
     * @return array
     */
    protected function compileRules(array $formData)
    {
        return $this->rules;
    }
}
