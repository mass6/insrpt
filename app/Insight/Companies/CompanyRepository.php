<?php

namespace Insight\Companies;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Insight\Users\ProfileRepository;
use Insight\Users\UserRepository;

/**
 * Class CompanyRepository
 * @package Insight\Companies
 */
class CompanyRepository
{

    /**
     * @return mixed
     */
    public function getAll()
    {
        return Company::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function findById($id)
    {
        return Company::find($id);
    }

    /**
     * @param int $num
     * @return mixed
     */
    public function getPaginated($num = 10)
    {
        return Company::paginate($num);
    }

    /**
     * @return mixed
     */
    public function getList()
    {
        return Company::lists('name', 'id');
    }

    /**
     * @return array
     */
    public function getCustomersList()
    {
        return ['' => '[Select]'] + Company::where('type', 'customer')->lists('name', 'id');
    }

    /**
     * @return mixed
     */
    public function getSuppliersList()
    {
        return Company::where('type', 'supplier')->lists('name', 'id');
    }

    /**
     * @param array $company
     * @return mixed
     */
    public function create(Array $company)
    {
        $newCompany = Company::create([
            'name'                      => $company['name'],
            'type'                      => $company['type'],
            'notes'                     => $company['notes'],
            'primary_contact_user_id'   => $company['primary_contact_user_id'],
            'magento_customer_group_id' => $company['magento_customer_group_id'],
            'address1_description'      => $company['address1_description'],
            'address1_body'             => $company['address1_body'],
            'address2_description'      => $company['address2_description'],
            'address2_body'             => $company['address2_body'],
            'address3_description'      => $company['address3_description'],
            'address3_body'             => $company['address3_body'],
            'address4_description'      => $company['address4_description'],
            'address4_body'             => $company['address4_body'],
            'settings'                  => $company['settings']
        ]);

        return $newCompany;

    }

    /**
     * @param $company
     * @return array
     */
    public function getAssociatedSuppliersList($company)
    {
        return ['' => '[Select One]'] + $company->suppliers->lists('name', 'id');

    }

    /**
     * @param $id
     */
    public function delete($id)
    {
        Company::destroy($id);
    }

    //get company's primary contact
    /**
     * @param $companyId
     * @return null
     */
    public function getPrimaryContact($companyId)
    {
        if ($companyId) {
            $company = Company::find($companyId);
            if ($company) {
                if ($company->primary_contact_user_id) {
                    return $company->primary_contact_user_id;
                }
            }
        }

        return null;
    }

    public function addSupplier($company, $supplier)
    {
        return $company->suppliers()->attach($supplier);
    }

    /**
     * @param $user_id
     */
    public function updatePrimaryContact($user_id)
    {
        $companies = $this->getAll();
        foreach ($companies as $company) {
            if ($company->primary_contact_user_id) {
                if ($company->primary_contact_user_id == $user_id) {
                    $company->primary_contact_user_id = null;
                    $company->save();
                }
            }
        }
    }

    /**
     * @param $company_id
     * @return bool
     */
    public function checkBeforeDeleting($company_id)
    {
        $profile = new ProfileRepository();
        $userRepository = new UserRepository($profile);
        $users = $userRepository->getAll();
        foreach ($users as $user) {
            if ($user->company_id == $company_id) {
                return false;
            }
        }

        return true;
    }
} 