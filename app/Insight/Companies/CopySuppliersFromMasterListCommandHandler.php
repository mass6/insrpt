<?php

namespace Insight\Companies;

use Exception;
use Illuminate\Support\Facades\Log;
use Laracasts\Commander\CommandHandler;

/**
 * Class CopySuppliersFromMasterListCommandHandler
 * @package Insight\Companies
 */
class CopySuppliersFromMasterListCommandHandler implements CommandHandler
{

    /**
     * Handle the command
     *
     * @param $command
     *
     * @return mixed
     */
    public function handle($command)
    {
        $company = $command->company;
        $currentSuppliers = $company->associatedSuppliers;

        $siteOwner = siteOwner();

        // Create the Company
        try {

            $numberOfSuppliersAdded = 0;
            foreach ($siteOwner->associatedSuppliers as $supplier) {

                $supplierAlreadyExists = false;
                foreach ($currentSuppliers as $currentSupplier) {
                    if ($supplier->name === $currentSupplier->name) {
                        $supplierAlreadyExists = true;
                    }
                }

                if ($supplierAlreadyExists) {
                    continue;
                }

                $newSupplier = new Supplier([
                    'name'            => $supplier->name,
                    'address'         => $supplier->address,
                    'email'           => $supplier->email,
                    'website'         => $supplier->website,
                    'primary_contact' => $supplier->primary_contact,
                    'telephone1'      => $supplier->telephone1,
                    'telephone2'      => $supplier->telephone2,
                    'faxe'            => $supplier->fax,
                    'description'     => $supplier->description,
                    'active'          => $supplier->active,
                ]);

                if ($company->associatedSuppliers()->save($newSupplier)) {
                    $numberOfSuppliersAdded++;
                }
            }

        } catch (Exception $e) {
            Log::info($e->getMessage());

            return false;
        }

        //$company->raise(new CompanyWasCreated($company));
        //$this->dispatchEventsFor($company);

        return $numberOfSuppliersAdded;
    }

}