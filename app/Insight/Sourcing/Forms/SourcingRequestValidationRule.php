<?php 

namespace Insight\Sourcing\Forms;

/**
 * Class SourcingRequestValidationRule
 * @package Insight\Sourcing\Forms
 */
class SourcingRequestValidationRule
{

    /**
     * @var
     */
    private $newSourcingRequestForm;

    /**
     * @param NewSourcingRequestForm $newSourcingRequestForm
     */
    public function __construct(NewSourcingRequestForm $newSourcingRequestForm)
    {
        $this->newSourcingRequestForm = $newSourcingRequestForm;
    }


    /**
     * Return the standard validation rules for creating a Sourcing Request
     *
     * @return array
     */
    public function getValidationRules()
    {
        return $this->newSourcingRequestForm->getValidationRules();
    }
}
 