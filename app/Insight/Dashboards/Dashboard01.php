<?php namespace Insight\Dashboards; 
/**
 * Insight Client Management Portal:
 * Date: 8/1/14
 * Time: 8:38 PM
 */
use Cartalyst\Sentry\Facades\Native\Sentry;
use Insight\Portal\Connections\Webservices;
use Insight\Companies\CompanyRepository;
use DateTime;
use Insight\Portal\Services\ProductsService;

class Dashboard01 
{
    /**
     * @var Webservices
     */
    protected $webservice;
    /**
     * @var ProductsService
     */
    private $productsService;


    /**
     * Dashboard01 constructor.
     *
     * @param ProductsService  $productsService
     */
    public function __construct(ProductsService $productsService)
    {
        $this->webservice = new Webservices();
        $this->productsService = $productsService;
    }

    public function _destructServiceSession(){
        $this->webservice->__destruct();
    }
    //get target categories
    public function getParentCategory(){
        $parentId = settings('dashboards.parent_category');
        return $parentId;
    }

    public function getOrdersDuringTime($parentId, $startTime, $endTime, $group_id = null){
        $order = $this->webservice->getOrdersDuringTime($parentId, $startTime, $endTime, $group_id);
        return $order;
    }

    public function getLoggedInUserInfo(){
        return Sentry::getUser();
    }

    public function showSpendPerCategory($user){
        $parentId = $this->getParentCategory();
        //$startTime and $endTime are the time period which user selects
        $startTime = date('Y-m-d 00:00:00', strtotime('first day of 2 months ago'));
        $endTime = date('Y-m-d H:i:s');
        $site_owner_id = settings('site_owner'); //36S company ID
        $result = null;
         if($user->company_id == $site_owner_id || $user->isSuperUser()){
             $result = $this->getOrdersDuringTime($parentId, $startTime, $endTime);
         } else {
             $companyRepositories = new CompanyRepository();
             $company = $companyRepositories->findById($user->company_id);
             if($company->magento_customer_group_id)
                $result = $this->getOrdersDuringTime($parentId, $startTime, $endTime, $company->magento_customer_group_id);
         }

        if(!$result)
            return $result;
        //$this->_destructServiceSession();
        $spend = array();
        foreach($result as $value){
            $subTotal = array(
                'category' => $value->category_name,
                'total' => $value->spend
            );
            array_push($spend,$subTotal);
        }
        return $spend;
    }

    /*
    * get products ordered last three months include the current month
    * limit 10 products each period
    */
    public function getProductsOrderedWidgetData($website = '')
    {
        $filters = array('report_period' => 'month');
        $filters['website'] = $website ?: '';
        $from = new DateTime('first day of 2 months ago');
        $to = new DateTime('today');
        $filters['report_from'] = $from->format('d-m-Y');
        $filters['report_to'] = $to->format('d-m-Y');
        $filters['limit'] = 10;
        $report = $this->productsService->getProductsOrdered($filters, $formatted = false);
        $report['url'] = route('portal.report.products-ordered', $report['meta']);
        return $report;
    }
} 