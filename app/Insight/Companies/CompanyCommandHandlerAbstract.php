<?php

namespace Insight\Companies;

use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class CompanyCommandHandlerAbstract
 * @package Insight\Companies
 */
abstract class CompanyCommandHandlerAbstract implements CommandHandler
{

    use DispatchableTrait;

    /**
     * @var CompanyRepository
     */
    protected $companyRepository;

    /**
     * Array of checkbox fields
     *
     * @var array
     */
    protected $checkboxFields = [
        'report-delivery.orders-pending-approval.enabled',
        'operations.materials-received-tracking.enabled',
    ];

    /**
     * @param CompanyRepository $companyRepository
     */
    public function __construct(CompanyRepository $companyRepository)
    {
        $this->companyRepository = $companyRepository;
    }

    /**
     * Sets values for checkbox fields to false if not present in input array
     *
     * @param $settingsInput
     * @return mixed
     */
    protected function setCheckboxValuesToFalseIfEmpty($settingsInput)
    {
        foreach ($this->checkboxFields as $field) {
            if (!array_get($settingsInput, $field)) {
                array_set($settingsInput, $field, false);
            }
        }

        return $settingsInput;
    }

} 