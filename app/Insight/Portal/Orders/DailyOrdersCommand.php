<?php
namespace Insight\Portal\Orders;

class DailyOrdersCommand{
    /**
     * @var array
     */
    public $portalOrders;
    /**
     * @var string
     */
    public $report_date;
    /**
     * @var string
     */
    public $customer;

    public $websiteCode;

    public $orderReportSettings;


    public function __construct(Array $portalOrders, $report_date, $customer, $websiteCode, $orderReportSettings)
    {
        $this->portalOrders = $portalOrders;
        $this->report_date = $report_date;
        $this->customer = $customer;
        $this->websiteCode = $websiteCode;
        $this->orderReportSettings = $orderReportSettings;
    }
}