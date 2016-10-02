<?php

namespace Insight\ProductRequests\Commands;

use Insight\Core\CommandBus;
use Insight\ProductRequests\Search\ProductRequestSearchIndex;
use Insight\Search\AlgoliaSearch;
use Laracasts\Commander\CommandHandler;

/**
 * Class DeleteProductRequestIndexCommandHandler
 * @package Insight\ProductRequests\Commands
 */
class DeleteProductRequestIndexCommandHandler implements CommandHandler
{

    use CommandBus;

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     * @throws ProductRequestFormException
     */
    public function handle($command)
    {
        $productRequest = $command->productRequest;

        $client = new AlgoliaSearch;
        $index = new ProductRequestSearchIndex($client);
        $index->deleteRecord($productRequest);
    }

}
