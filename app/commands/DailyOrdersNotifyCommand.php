<?php
use Illuminate\Console\Command;
use Insight\Companies\Company;
use Insight\Libraries\DatePeriod;
use Insight\Portal\Orders\DailyOrderReport;
use Insight\Portal\Orders\DailyOrdersCommandRestHandler;
use Insight\Portal\Orders\DailyOrdersCommand;
use Insight\Portal\Connections\Webservices;
use Insight\Portal\Services\OrderService;
use Symfony\Component\Console\Input\InputArgument;

class DailyOrdersNotifyCommand extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'insight:daily-orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls daily orders from the portal';

    /**
     * @var
     */
    protected $customers;
    /**
     * @var Insight\Portal\Connections\Webservices
     */
    private $webservices;
    /**
     * @var OrderService
     */
    private $orderService;


    /**
     * @param \Insight\Portal\Connections\Webservices $webservices
     * @param OrderService                            $orderService
     */
    public function __construct(Webservices $webservices, OrderService $orderService)
    {
        parent::__construct();
        $this->webservices = $webservices;
        $this->orderService = $orderService;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function fire()
    {
        $this->info('//////////////////// START ///////////////////////' . "\r\n");
        // Determine the report date
        $report_date = $this->argument('report_date') ? : DatePeriod::today();
        if ($report_date === strtolower('yesterday')) {
            $report_date = DatePeriod::yesterday();

        }

        // Pull daily orders
        $this->info("Querying Daily Orders for: " . $report_date . "\r\n");
        $this->customers = $this->webservices->getCustomerGroups();
        foreach($this->customers as $customer){
            $website_code = $this->getWebsiteCode($customer);
            if ($website_code && $this->reportIsEnabledForCustomer($customer)) {
                $dailyOrders = $this->orderService->getDailyOrders($report_date, strtolower($website_code));
                $this->info($customer->customer_group_code . " Daily Orders: " . count($dailyOrders));
                $orderReportSettings = $this->getOrderReportSettings($customer);

                // notify users
                if ( count($dailyOrders) > 0 && $orderReportSettings)
                {
                    $command = new DailyOrdersCommand($dailyOrders, $report_date, $customer, $website_code, $orderReportSettings);
                    $handler = new DailyOrdersCommandRestHandler;
                    $results = $handler->handle($command);
                    $this->info($results);
                    $this->info("All orders of ". $customer->customer_group_code ." have been pulled" . "\r\n");
                }
                else
                {
                    $this->info("No orders of ". $customer->customer_group_code ." to be pulled" . "\r\n");
                }
            }
        }

        $this->info('///////////////////// END ////////////////////////');
    }


    /**
     * Determines the website code to be used for order report queries. If no website group is defined,
     * return the customer group code.
     *
     * @param $customer
     * @return null
     */
    protected function getWebsiteCode($customer)
    {
        $company = Company::where('magento_customer_group_id', $customer->customer_group_id)->first();
        if ($company) {
            return $company->settings()->get('portal.website_code') ? : $customer->customer_group_code;
        }

        return null;
    }

    protected function reportIsEnabledForCustomer($customer)
    {
        $company = Company::where('magento_customer_group_id', $customer->customer_group_id)->first();
        if ($company) {
            return $company->settings()->get('order_report.enabled');
        }

        return false;
    }

    protected function getOrderReportSettings($customer)
    {
        $company = Company::where('magento_customer_group_id', $customer->customer_group_id)->first();
        if ($company) {
            $orderReportSettings = $company->settings()->get('order_report');
            if (! array_get($orderReportSettings, 'field_definitions', null)) {

                return DailyOrderReport::getDefaultFields();
            }

            return $orderReportSettings;
        }
    }

    /**
     * Get the console command arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return array(
            array('report_date', InputArgument::OPTIONAL, 'Report date argument. (Format: yyyy-mm-dd)'),
        );
    }

    /**
     * Get the console command options.
     *
     * @return array
     */
    protected function getOptions()
    {
        return array(
            //array('example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null),
        );
    }

}