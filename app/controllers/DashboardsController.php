<?php

use Insight\Dashboards\Dashboard01;
use Portal\PortalController;

class DashboardsController extends PortalController {

    /**
    * @var Dashboard01
     */
    protected $dashboard;

    public function __construct(Dashboard01 $dashboard)
    {
        $this->dashboard = $dashboard;
        parent::__construct();

        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('dashboards')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        });
    }


    /**
     * @return mixed
     */
    public function home()
	{
        $loginUser = $this->user;
        $spendPerCategories = $this->dashboard->showSpendPerCategory($loginUser);
//        $ordersTodaySum = $this->portal->getReport('OrdersTodaySum', 'array')[0];
        $ordersPendingApproval = $this->portal->getOrdersPendingApprovalSum($this->group);
        $currentMonthsOrders = $this->portal->getCurrentMonthsOrdersSum($this->group);
        $dailyOrderTotalsThisMonth = $this->portal->getDailyOrderTotalsThisMonth($this->group);
        $thirdPartyOrdersThisMonthSum = $this->portal->getThirdPartyOrdersThisMonthSum();
//        $spendPerCategoryThisMonthSum = $this->portal->getReport('SpendPerCategoryThisMonthSum', 'array');
        if (empty($dailyOrderTotalsThisMonth)) {
            $dailyOrderTotalsThisMonth = [['day' => '0', 'count' => 0, 'total' => 0 ]];
        }
//        if (empty($spendPerCategoryThisMonthSum)) {
//            $spendPerCategoryThisMonthSum = [['category' => 'No data yet', 'total' => '0' ]];
//        }
        if (empty($spendPerCategories)) {
            $spendPerCategories = [['category' => 'No data yet', 'total' => '0' ]];
        }
        $productsOrdered = $this->dashboard->getProductsOrderedWidgetData($this->getCustomerWebsiteCode());

        JavaScript::put([
//            'ordersTodayCount' => $ordersTodaySum['count'],
//            'ordersTodayValue' => $ordersTodaySum['sum'],
            'pendingApprovalCount' => $ordersPendingApproval['count'],
            'pendingApprovalValue' => (float)$ordersPendingApproval['sum'],
            'monthlyOrderCount' => $currentMonthsOrders['count'],
            'monthlyOrderValue' => (float)$currentMonthsOrders['sum'],
            'dailyOrderTotals' => $dailyOrderTotalsThisMonth,
            'thirdPartyOrderCount' => $thirdPartyOrdersThisMonthSum['count'],
            'thirdPartyOrderValue' => (float)$thirdPartyOrdersThisMonthSum['sum'],
//            'spendPerCategory' =>  $spendPerCategoryThisMonthSum
            'spendPerCategory' =>  $spendPerCategories,
            'productsOrdered' => $productsOrdered
        ]);

        return View::make('dashboards.dashboard_02', array('title' => 'Dashboards', 'productsOrderedReportUrl' => $productsOrdered['url']));
	}

}
