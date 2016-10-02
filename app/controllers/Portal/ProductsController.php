<?php namespace Portal;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Insight\Companies\Company;
use Insight\Portal\Services\PortalDataService;
use Insight\Portal\Services\ProductsService;
use Laracasts\Flash\Flash;
use Laracasts\Utilities\JavaScript\Facades\JavaScript;

/**
 * Class ProductsController
 * @package Portal
 */
class ProductsController extends PortalController
{

    /**
     * @var
     */
    private $website_code;

    /**
     * @var ProductsService
     */
    private $productsService;

    /**
     * @var PortalDataService
     */
    private $portalDataService;


    /**
     * @param ProductsService   $productsService
     * @param PortalDataService $portalDataService
     */
    public function __construct(ProductsService $productsService, PortalDataService $portalDataService)
    {
        $this->beforeFilter(function () {
            if ( ! $this->user->hasAccess('portal.products') || ( ! $this->user->company->settings()->get('portal.website_code') && ! isSiteOwner($this->user) )) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        });

        parent::__construct();
        $this->website_code = $this->user->company->settings()->get('portal.website_code');
        $this->productsService = $productsService;
        $this->portalDataService = $portalDataService;
    }


    /**
     * @param null $customer_id
     *
     * @return mixed
     */
    public function getProducts($customer_id = null)
    {
        if (isNotSiteOwner($this->user)) {
            $customer_id = $this->company->id;
        } else {
            $customers = $this->getCustomersFilter();
            if ($customer_id && $customer_id !== 'all') {
                if ( ! ( Company::find($customer_id) )) {
                    Flash::error('Customer not found.');

                    return Redirect::home();
                }
            }
        }
        $exportable = $this->user->hasAccess('portal.products.export');

        return View::make('portal.products.index', compact('customer_id', 'customers', 'exportable'));
    }


    /**
     * @return array
     */
    protected function getCustomersFilter()
    {
        $customerFilter = [ ];
        $customers      = Company::whereType('customer')->get();
        foreach ($customers as $customer) {
            if ($customer->settings()->get('portal.store')) {
                $customerFilter[$customer->id] = $customer->name;
            }
        }
        asort($customerFilter);

        return $customerFilter;
    }


    /**
     * @param $customer_id
     *
     * @return bool|mixed
     */
    public function getProductsFilter($customer_id = null)
    {
        if ( (!$customer_id || (int) $customer_id !== (int) $this->company->id)  && isNotSiteOwner($this->user)) {
            return false;
        }

        return $this->productsService->getLocalProducts($customer_id);
    }

    /**
     * @return mixed
     */
    public function getProductsOrderedReport()
    {
        if (Request::ajax()) {
            $filters            = Input::all();
            $filters['website'] = array_get($filters, 'website', $this->website_code ?: '');

            return $this->getProductsOrderedReportData($filters);
        }

        $defaultWebsite = $this->website_code;
        $suppliers      = $this->user->hasAccess('portal.orders.products-ordered.filter.suppliers') ? $this->portal->getSuppliers() : [ ];
        $filters        = $this->getFilters();
        $contracts      = $this->portalDataService->getWebsiteContracts($defaultWebsite);
        JavaScript::put([ 'contracts' => $contracts['websiteContracts'], 'default_website' => $defaultWebsite ]);

        return View::make('portal.report.products_ordered',
            [ 'websites' => $contracts['websites'], 'suppliers' => $suppliers, 'filters' => $filters ]);
    }


    /**
     * @param $filters
     *
     * @return mixed
     */
    protected function getProductsOrderedReportData($filters)
    {
        $report        = $this->productsService->getProductsOrdered($filters);
        $headerRowSpan = count($report['periods']) ? 2 : 1;

        return View::make('portal.report.products_ordered_table',
            array_merge($report, [ 'headerRowSpan' => $headerRowSpan ]));
    }


    /**
     * @return mixed
     */
    protected function getFilters()
    {
        $filters['report_from']   = Input::get('report_from', '');
        $filters['report_to']     = Input::get('report_to', '');
        $filters['report_period'] = Input::get('report_period', 'month');
        $filters['website']       = Input::get('website', $this->website_code);
        $filters['contract']      = Input::get('contract', '');
        $filters['supplier']      = Input::get('supplier', '');

        return $filters;
    }

}
