<?php

namespace Insight\Companies;

use Exception;
use Insight\Companies\Events\CompanyWasCreated;

/**
 * Insight Client Management Portal:
 * Date: 7/28/14
 * Time: 10:29 PM
 */
class AddNewCompanyCommandHandler extends CompanyCommandHandlerAbstract
{

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {

        // Create the Company
        try {
            $company = $this->companyRepository->create([
                'name'                      => $command->name,
                'type'                      => $command->type,
                'notes'                     => $command->notes,
                'primary_contact_user_id'   => $command->primary_contact_user_id,
                'magento_customer_group_id' => $command->magento_customer_group_id,
                'address1_description'      => $command->address1_description,
                'address1_body'             => $command->address1_body,
                'address2_description'      => $command->address2_description,
                'address2_body'             => $command->address2_body,
                'address3_description'      => $command->address3_description,
                'address3_body'             => $command->address3_body,
                'address4_description'      => $command->address4_description,
                'address4_body'             => $command->address4_body,
                'settings'                  => json_encode($this->setCheckboxValuesToFalseIfEmpty($command->settings)),
            ]);

        } catch (Exception $e) {
            return 'Could not create company.';
        }

        $company->raise(new CompanyWasCreated($company));
        $this->dispatchEventsFor($company);

    }

}