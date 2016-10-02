<?php

namespace Insight\Companies;

use Insight\Companies\Events\CompanyWasUpdated;

/**
 * Class UpdateCompanyCommandHandler
 * @package Insight\Companies
 */
class UpdateCompanyCommandHandler extends CompanyCommandHandlerAbstract
{

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $company = $this->companyRepository->findById($command->id);

        $company->name = $command->name;
        $company->type = $command->type;
        $company->notes = $command->notes;
        $company->primary_contact_user_id = $command->primary_contact_user_id;
        $company->address1_description = $command->address1_description;
        $company->address1_body = $command->address1_body;
        $company->address2_description = $command->address2_description;
        $company->address2_body = $command->address2_body;
        $company->address3_description = $command->address3_description;
        $company->address3_body = $command->address3_body;
        $company->address4_description = $command->address4_description;
        $company->address4_body = $command->address4_body;
        $company->magento_customer_group_id = $command->magento_customer_group_id;
        $company->settings()->merge($this->setCheckboxValuesToFalseIfEmpty($command->settings));
        $company->save();
        $company->suppliers()->sync($command->supplier_ids ?:[]);
        $company->raise(new CompanyWasUpdated($company));
        $this->dispatchEventsFor($company);

    }
}