<?php

use Illuminate\Console\Command;
use Insight\Portal\Services\PortalDataService;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Insight\Portal\Contracts\UpdateContractsCommandHandler;
use Insight\Portal\Contracts\UpdateContractsCommand;

class VerifyContractsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'insight:verify-contracts';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Verifies that local contract data matches latest contract data from portal.';
    /**
     * @var Insight\Portal\Repositories\PortalRepository
     */
    private $portal;
    /**
     * @var Insight\Portal\Contracts\Contract
     */
    private $contract;

	/**
	 * @var PortalDataService
	 */
	private $portalDataService;


	/**
	 * @param PortalDataService                             $portalDataService
	 * @param \Insight\Portal\Contracts\ContractRepository  $contract
	 */
    public function __construct(PortalDataService $portalDataService, Insight\Portal\Contracts\ContractRepository $contract)
	{
		parent::__construct();
        $this->contract = $contract;
		$this->portalDataService = $portalDataService;
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // fetch array of local contract and remote contract
        $localContracts = $this->contract->getAll()->toArray();
        $portalContracts = $this->portalDataService->getContracts();

        $this->info('Local: ' . count($localContracts) . '  Portal: ' . count($portalContracts));

        $command = new UpdateContractsCommand($localContracts, $portalContracts);

        $handler = new UpdateContractsCommandHandler;
        $results = $handler->handle($command);
        $this->info($results);





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
