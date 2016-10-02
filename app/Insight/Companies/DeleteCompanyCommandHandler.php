<?php

namespace Insight\Companies;

use Insight\Companies\Events\CompanyWasDeleted;

/**
 * Class DeleteCompanyCommandHandler
 * @package Insight\Companies
 */
class DeleteCompanyCommandHandler extends CompanyCommandHandlerAbstract
{

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $savedCompany = clone $command->company;

        $deleteChecking = $this->companyRepository->checkBeforeDeleting($command->company->id);
        if (!$deleteChecking) {
            return $deleteChecking;
        }

        $this->companyRepository->delete($command->company->id);
        $savedCompany->raise(new CompanyWasDeleted($savedCompany));
        $this->dispatchEventsFor($savedCompany);

        return true;
    }
}