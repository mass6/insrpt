<?php namespace Portal;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Paginator;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\View;
use Insight\Portal\Services\OrderService;

/**
 * Class OrdersController
 * @package Portal
 */
class OrdersController extends PortalController
{

    /**
     * @var OrderService
     */
    private $orderService;


    /**
     * @param OrderService     $orderService
     */
    public function __construct(OrderService $orderService)
    {
        $this->beforeFilter(function () {
            if ( ! $this->user->hasAccess('portal.orders')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        });
        $this->beforeFilter(function () {
            if ( ! $this->user->hasAccess('portal.approvals')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        }, [ 'only' => 'getApprovalStatistics' ]);

        parent::__construct();
        $this->orderService = $orderService;
    }


    /**
     * @param null $customer
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedToday($customer = null, $contractGroup = null)
    {
        if (Request::ajax()) {
            return $this->orderService->getOrdersApprovedToday($customer, $contractGroup);
        }

        return $this->getOrderReportResponse($customer, $contractGroup);
    }


    /**
     * @param $customer
     * @param $contractGroup
     *
     * @return mixed
     */
    protected function getOrderReportResponse($customer, $contractGroup)
    {
        $customer = $this->getCustomerDataGroup($customer);

        return View::make('portal.orders.index', compact('customer', 'contractGroup'));
    }


    /**
     * @param null $customer
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedYesterday($customer = null, $contractGroup = null)
    {
        if (Request::ajax()) {
            return $this->orderService->getOrdersApprovedYesterday($customer, $contractGroup);
        }

        return $this->getOrderReportResponse($customer, $contractGroup);
    }


    /**
     * @param null $customer
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedThisMonth($customer = null, $contractGroup = null)
    {
        if (Request::ajax()) {
            return $this->orderService->getOrdersApprovedThisMonth($customer, $contractGroup);
        }

        return $this->getOrderReportResponse($customer, $contractGroup);
    }


    /**
     * @param null $customer
     * @param null $contractGroup
     *
     * @return array
     */
    public function getThirdPartyOrdersApprovedThisMonth($customer = null, $contractGroup = null)
    {
        if (Request::ajax()) {
            return $this->orderService->getThirdPartyOrdersApprovedThisMonth($customer, $contractGroup);
        }

        return $this->getOrderReportResponse($customer, $contractGroup);
    }


    /**
     * @param null $customer
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedLastMonth($customer = null, $contractGroup = null)
    {
        if (Request::ajax()) {
            return $this->orderService->getOrdersApprovedLastMonth($customer, $contractGroup);
        }

        return $this->getOrderReportResponse($customer, $contractGroup);
    }


    /**
     * @param null $customer
     * @param null $contractGroup
     *
     * @return array
     */
    public function getOrdersApprovedYearToDate($customer = null, $contractGroup = null)
    {
        if (Request::ajax()) {
            return $this->orderService->getOrdersApprovedYearToDate($customer, $contractGroup);
        }

        return $this->getOrderReportResponse($customer, $contractGroup);
    }


    /**
     * @param null $customer
     *
     * @return array
     */
    public function getOrdersPendingApproval($customer = null)
    {
        $customer = $this->getPortalCustomerGroupId();
        if (Request::ajax()) {
            return $this->orderService->getOrdersPendingApproval($customer);
        }

        return View::make('portal.approvals.index')->with([ 'reportName' => 'approvals' ]);
    }


    /**
     * @return mixed
     */
    public function getApprovalStatistics()
    {
        $dataGroup          = ! isSiteOwner($this->user) ? $this->user->company->settings()->get('portal.dataGroup') : null;
        $approvalStatistics    = $this->orderService->getApprovalStatistics($dataGroup);

        return View::make('portal.approvals.statistics', compact('approvalStatistics'));
    }


    /**
     * get approved orders with custom filters
     */
    public function getCustomOrderReport()
    {
        if (Request::ajax()) {
            return $this->orderService->getCustomOrderReport(Input::all());
        }

        $performSearch = Input::get('search', null);
        $filters       = [
            'report_name'    => 'Custom',
            'customer_group' => $this->user->company->id == siteOwnerId() ? Input::get('customer_group',
                '') : $this->getPortalCustomerGroupId(),
            'contract_group' => Input::get('contract_group', ''),
            'supplier'       => Input::get('supplier', ''),
            'date'           => Input::get('date', ''),
            'from'           => Input::get('from', ''),
            'to'             => Input::get('to', '')
        ];

        return View::make('portal.orders.custom', compact('filters', 'performSearch'));
    }


    /**
     * @return array
     */
    public function getCustomOrderLinesReport()
    {
        if (Request::ajax()) {
            return $this->orderService->getCustomOrderLinesReport(Input::all());
        }

        $performSearch = Input::get('search', 0);
        $filters       = [
            'report_name'    => 'CustomProductsReport',
            'customer_group' => $this->company->id == siteOwnerId() ? Input::get('customer_group',
                '') : $this->getPortalCustomerGroupId(),
            'contract_group' => Input::get('contract_group', ''),
            'supplier'       => Input::get('supplier', ''),
            'date'           => Input::get('date', ''),
            'from'           => Input::get('from', ''),
            'to'             => Input::get('to', '')
        ];

        return View::make('portal.report.products_custom', compact('filters', 'performSearch'));
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function getOrderDetails($id)
    {
        $order = $this->orderService->getOrderDetails($id);

        return View::make('portal.orders.show', compact('order'));
    }


    /**
     * @param $id
     *
     * @return mixed
     */
    public function printOrder($id)
    {
        $order = $this->orderService->getOrderDetails($id);

        return View::make('portal.orders.print', compact('order', 'items', 'comments'));
    }


    /**
     * @return mixed
     */
    public function searchRouter()
    {
        // grab the search term
        $searchTerm = Input::get('s', null);
        $date       = Input::get('date');
        $from       = Input::get('from');
        $to         = Input::get('to');
        if ($searchTerm || ( $date != 'all' && ( $from || $to ) )) {
            $searchResults = $this->orderService->getOrderSearchResults($this->getPortalCustomerGroupId(), $searchTerm, $from, $to);
            //$this->orderService->searchOrder(Input::all());
            $perPage       = 12;
            $currentPage   = Input::get('page') - 1;
            $pagedData     = array_slice($searchResults, $currentPage * $perPage, $perPage);
            $results       = Paginator::make($pagedData, count($searchResults), $perPage);
            $s             = $searchTerm;
            $results->appends(compact('s', 'date', 'from', 'to')); // appends query strings to pagination links
        }

        return View::make('portal.orders.search', compact('results', 'searchTerm', 'date', 'from', 'to'));
    }


    /**
     * @param $searchTerm
     *
     * @return mixed
     */
    public function searchOrder($searchTerm)
    {
        $results = null;
        $date    = Input::get('date');
        $from    = Input::get('from');
        $to      = Input::get('to');
        if ($searchTerm) {
            $searchResults = $this->orderService->getOrderSearchResults($this->getPortalCustomerGroupId(), $searchTerm, $from, $to);
            $perPage       = 12;
            $currentPage   = Input::get('page') - 1;
            $pagedData     = array_slice($searchResults, $currentPage * $perPage, $perPage);
            $results       = Paginator::make($pagedData, count($searchResults), $perPage);
            $results->appends(compact('date', 'from', 'to')); // appends query strings to pagination links
        }

        return View::make('portal.orders.search', compact('results', 'searchTerm', 'date', 'from', 'to'));
    }

}
