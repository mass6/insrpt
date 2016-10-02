<?php

use AlgoliaSearch\Client;
use Doctrine\Common\Proxy\Exception\InvalidArgumentException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\App;
use Insight\Portal\Products\Search\ProductSearchIndex;
use Insight\ProductRequests\Search\ProductRequestSearchIndex;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class CreateSearchIndexCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'insight:create-search-index';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Perform an initial search index import.';

    /**
     * @var Client
     */
    private $client;


    /**
     * Create a new command instance.
     *
     */
	public function __construct()
	{
		parent::__construct();
    }

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
        // increase memory limit for large indexes
		ini_set('memory_limit','2048M');
        $indexClass = $this->getModelClass($this->argument('model'));
        $indexClass->buildInitialIndex();
        $this->info('done');
	}

    protected function getModelClass($model)
    {
        $this->info($model);
        switch (strtolower($model)) {
            case 'productrequest':
                $indexClass = ProductRequestSearchIndex::class;
                break;
            case 'product':
                $indexClass = ProductSearchIndex::class;
                break;
            default:
                throw new InvalidArgumentException($model . ' is not a known index model.');
        }
        $this->info($indexClass);

        return App::make($indexClass);
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('model', InputArgument::REQUIRED, 'name of model to index.'),
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
