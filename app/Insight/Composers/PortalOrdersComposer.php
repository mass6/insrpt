<?php namespace Insight\Composers;

use Illuminate\Http\Request;
use Insight\Portal\Services\PortalDataService;
use Laracasts\Utilities\JavaScript\Facades\JavaScript;

/**
 * Class PortalOrdersComposer
 * @package Insight\Composers
 */
class PortalOrdersComposer
{

    /**
     * @var Request
     */
    private $request;

    /**
     * @var PortalDataService
     */
    private $portalDataService;


    /**
     * PortalOrdersComposer constructor.
     *
     * @param Request           $request
     * @param PortalDataService $portalDataService
     */
    public function __construct(Request $request, PortalDataService $portalDataService)
    {
        $this->request           = $request;
        $this->portalDataService = $portalDataService;
    }


    /**
     * @param $view
     */
    public function compose($view)
    {
        $view->with('heading', 'Orders: ' . $this->getHeading())->with('period',
                $this->request->segment(3))->with('customers', $customers = $this->getPortalCustomersList());

        JavaScript::put([ 'customer_groups' => $customers ]);
    }


    /**
     * @return string
     */
    private function getHeading()
    {
        return ucwords(str_replace('-', ' ', $this->request->segment(3)));
    }


    /**
     * @return array
     */
    protected function getPortalCustomersList()
    {
        return $this->portalDataService->getCustomerContractGroups($this->portalDataService->getPortalCustomerGroupId());
    }
}
