<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Insight\Portal\Orders\UpdateOrdersCommandHandler;
use Insight\Portal\Orders\UpdateOrdersCommand;

class SyncOrdersCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'insight:sync-orders';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Pulls new orders from the portal, saves them, and marks the orders as synced';

    /**
     * @var Insight\Portal\Connections\Webservices
     */
    private $webservices;

    /**
     * @param \Insight\Portal\Connections\Webservices $webservices
     */
    public function __construct(Insight\Portal\Connections\Webservices $webservices)
    {
		parent::__construct();
        $this->webservices = $webservices;
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
            // Pull new orders
	    $portalOrders = $this->webservices->getOrders();
            $this->info('Orders to sync: ' . count($portalOrders));

		// Save orders, notify users
		if ( count($portalOrders) > 0)
		{
			foreach ($portalOrders as $portalOrder) {
				$orders = [$portalOrder];
				$command = new UpdateOrdersCommand($orders);
				$handler = new UpdateOrdersCommandHandler;
				$results = $handler->handle($command);
				$this->info($results);
			}
			$this->info('All Orders saved');
		}
	    else
	    {
			$this->info('No Orders saved');
	    }

		// Mark orders as synced
		foreach ($portalOrders as $order) {
        	$this->webservices->markOrderSynced($order->increment_id);
	    }

		$this->info('All Orders synced');

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

}
