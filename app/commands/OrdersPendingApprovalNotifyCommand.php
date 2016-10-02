<?php

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Insight\Companies\Company;
use Insight\Portal\Orders\CreateOrdersPendingApprovalReportCommand;
use Insight\Portal\Orders\CreateOrdersPendingApprovalReportCommandHandler;
use Insight\Portal\Services\OrderService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

/**
 * Class OrdersPendingApprovalNotifyCommand
 */
class OrdersPendingApprovalNotifyCommand extends Command {

	const MIN_LEAD_TIME_HOURS = 24;
	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'insight:orders-pending-approval';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = "Pulls a report of all orders pending approval per customer and emails it to each customer's configured users.";

	/**
	 * @var OrderService
	 */
	private $orderService;


	/**
	 * Create a new command instance.
	 *
	 * @param OrderService $orderService
	 */
	public function __construct(OrderService $orderService)
	{
		parent::__construct();
		$this->orderService = $orderService;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{

		$this->info('Orders Pending Approval');

		$orders = $this->getOrdersPendingApproval();

		$customersToNotify = $this->customersToNotify();

		if (count($orders)) {
			$customerOrders = $this->mapCustomerOrders($orders, $customersToNotify);

			$this->createReport($customerOrders);
		}

	}

	/**
	 * @return array
	 */
	protected function customersToNotify()
	{
		$companies = Company::all();
		$customersToNotify = [ ];
		foreach ($companies as $company) {
			$dataGroup = $company->settings()->get('portal.dataGroup');
			$recipients = $company->settings()->get('report-delivery.orders-pending-approval.recipients');
			if ($dataGroup
				&& $company->settings()->get('report-delivery.orders-pending-approval.enabled')
				&& $recipients
			) {
				$customersToNotify[strtolower($dataGroup)] = [
					'customer_id'             => $company->id,
					'customer_name'           => $company->name,
					'dataGroup'      => strtolower($dataGroup),
					'recipients' => $recipients
				];
			}
		}

		return $customersToNotify;
	}

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			//array('example', InputArgument::REQUIRED, 'An example argument.'),
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

	/**
	 * @return array
	 */
	protected function getOrdersPendingApproval()
	{
		return $this->orderService->getOrdersPendingApproval();
	}

	/**
	 * @param $orders
	 * @param $customersToNotify
	 *
	 * @return mixed
	 */
	protected function mapCustomerOrders($orders, $customersToNotify)
	{
		foreach ($orders as $order) {
			if ($order['lead_time_hours'] >= self::MIN_LEAD_TIME_HOURS && key_exists(strtolower($order['customer']),
					$customersToNotify)
			) {
				$customersToNotify[strtolower($order['customer'])]['orders'][] = $order;
			}
		}

		return $customersToNotify;
	}

	/**
	 * @param $customersToNotify
	 */
	protected function createReport($customersToNotify)
	{
		foreach ($customersToNotify as $customerData) {
            if (array_get($customerData, 'orders')) {
                $handler = new CreateOrdersPendingApprovalReportCommandHandler;
                $handler->handle(new CreateOrdersPendingApprovalReportCommand($customerData));
            }
		}
	}

}
