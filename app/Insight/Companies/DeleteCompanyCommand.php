<?php

namespace Insight\Companies;

/**
 * Class DeleteCompanyCommand
 * @package Insight\Companies
 */
class DeleteCompanyCommand
{

    /**
     * @var
     */
    public $company;

    /**
     * @param $company
     */
    public function __construct($company)
    {
        $this->company = $company;
    }
} 