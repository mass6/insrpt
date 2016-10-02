<?php namespace Portal;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Insight\Portal\Services\PortalDataService;

class PortalManagementController extends PortalController
{

    /**
     * @var PortalDataService
     */
    private $portalDataService;


    public function __construct(PortalDataService $portalDataService)
    {
        parent::__construct();
        $this->portalDataService = $portalDataService;
    }

    /**
     * @return mixed
     */
    public function getUsers()
    {
        if (Request::ajax()) {
            $dataGroup = ! isSiteOwner($this->user) ? $this->user->company->settings()->get('portal.dataGroup') : null;

            return $this->portalDataService->getUsers($dataGroup);
        }

        return View::make('portal.users.index')->with([ 'reportName' => 'users' ]);
    }


    /**
     * @param null $group
     *
     * @return mixed
     */
    public function getContracts($group = null)
    {
        if (Request::ajax()) {
            return $this->portalDataService->getContracts($group);
        }

        $customers = $this->getPortalCustomersList();
        $group     = $this->getCustomerDataGroup($group);

        return View::make('portal.contracts.index', compact('group', 'customers'));

    }


    public function getDoa()
    {
        $dataGroup = ! isSiteOwner($this->user) ? $this->user->company->settings()->get('portal.dataGroup') : null;
        $doa       = $this->portalDataService->getDoa($dataGroup);

        return View::make('portal.doa', compact('doa'));
    }

}
