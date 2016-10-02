<?php

namespace Insight\Companies;

/**
 * Class CopySuppliersFromMasterListCommand
 * @package Insight\Companies
 */
class CopySuppliersFromMasterListCommand
{

    /**
     * @var Company
     */
    public $company;


    /**
     * @param Company $company
     */
    public function __construct(Company $company)
    {

        $this->company = $company;
    }
} 