<?php namespace Portal;

use Illuminate\Support\Facades\App;
use Insight\Core\CommandBus;

/**
 * Class PortalController
 */
abstract class PortalController extends \BaseController
{

    use CommandBus;

    /**
     * @var Insight\Portal\Repositories\PortalRepository
     */
    protected $portal;

    /**
     * @var
     */
    protected $group;


    /**
     * param PortalRepository $portal
     */
    public function __construct()
    {
        parent::__construct();
        $this->portal = App::make('Insight\Portal\Repositories\PortalRepository');
        $this->group  = $this->getCustomerDataGroup();
    }


    /**
     * @param $group
     *
     * @return mixed
     */
    protected function getCustomerDataGroup($group = null)
    {
        if ( ! $group && ! isSiteOwner($this->user)) {
            $group = $this->user->company->settings()->get('portal.dataGroup');
        }

        return $group;
    }


    /**
     * @return array
     */
    protected function getCustomerWebsiteCode()
    {
        return $this->user->company->settings()->get('portal.website_code');
    }

    /**
     * @return string
     */
    public function getPortalCustomerGroupId()
    {
        return isNotSiteOwner($this->user) ? $this->user->company->magento_customer_group_id : null;

    }


    /**
     * @return array
     */
    protected function getPortalCustomersList()
    {
        return $this->portal->getCustomerContractGroups($this->getPortalCustomerGroupId());
    }

}
