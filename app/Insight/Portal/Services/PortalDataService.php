<?php

namespace Insight\Portal\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Insight\Portal\Repositories\PortalRepository;

class PortalDataService
{

    /**
     * @var PortalRepository
     */
    private $portalRepository;


    /**
     * PortalService constructor.
     *
     * @param PortalRepository $portalRepository
     */
    public function __construct(PortalRepository $portalRepository)
    {
        $this->portalRepository = $portalRepository;
    }

    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    |
    */

    
    /**
     * @return string
     */
    public function getPortalCustomerGroupId()
    {
        if (Session::has('company')) {
            return Session::get('company')->id !== siteOwnerId() ? Session::get('company')->magento_customer_group_id : '';
        }

        return 'deadbeef';
    }

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getUsers($dataGroup = null)
    {

        $response = $this->portalRepository->getUsers($dataGroup);
        $users  = $response;

        foreach ($users as $key => $userData) {
            if ($userData['last_login'] !== 'Never') {
                $users[$key]['last_login'] = Carbon::createFromFormat('Y-m-d H:i:s',
                    $userData['last_login'])->diffInDays();
            }
        }

        return $users;
    }


    /*
    |--------------------------------------------------------------------------
    | Contracts
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getContracts($dataGroup = null)
    {
        $contracts = $this->portalRepository->getContracts($dataGroup);
        foreach ($contracts as $index => $contract) {
            if (key_exists('website_code', $contract)) {
                unset( $contracts[$index]['website_code'] );
            }
        }

        return $contracts;
    }

    /**
     * @param string $website get contract for specific website, leave blank to get the contracts of all websites
     *                        get the portal websites and their contracts
     *
     * @return mixed
     */
    public function getWebsiteContracts($website = '')
    {
        $contracts = $this->portalRepository->getContracts();

        $websites         = [ ];
        $websiteContracts = [ ];
        foreach ($contracts as $contract) {
            if ( ! $contract['website_code'] || ( $website && $contract['website_code'] != $website )) {
                continue;
            }

            $websites[$contract['website_code']] = $contract['website'];
            if ( ! isset( $websiteContracts[$contract['website_code']] )) {
                $websiteContracts[$contract['website_code']] = [ ];
            }
            $websiteContracts[$contract['website_code']][] = [
                'contract_id'   => $contract['web_id'],
                'contract_name' => $contract['name']
            ];
        }

        return compact('websites', 'websiteContracts');
    }

    /**
     * @param $portalCustomerGroupId
     *
     * @return array
     */
    public function getCustomerContractGroups($portalCustomerGroupId)
    {
        $contractGroups = $this->portalRepository->getContractGroups();
        $groupsData     = [ ];
        foreach ($contractGroups as $group) {
            // if there is a customer group Id specified, get only its contract groups, otherwise, get all
            if ($portalCustomerGroupId && $group['customer_group_id'] != $portalCustomerGroupId) {
                continue;
            }
            $groupsData[$group['customer_group_code']] = [
                'customer_group_id'   => $group['customer_group_id'],
                'customer_group_code' => $group['customer_group_code'],
                'contracts_groups'    => [ ]
            ];
            foreach ($group['contracts_groups'] as $contractGroup) {
                $groupsData[$group['customer_group_code']]['contracts_groups'][$contractGroup['name']] = $contractGroup['group_id'];
            }
        }
        ksort($groupsData);

        return $groupsData;
    }


    /*
    |--------------------------------------------------------------------------
    | DOA
    |--------------------------------------------------------------------------
    |
    */

    /**
     * @param null $dataGroup
     *
     * @return array
     */
    public function getDoa($dataGroup = null)
    {
        return $this->portalRepository->getDoa($dataGroup);
    }
}
