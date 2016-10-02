<?php

namespace Insight\ProductRequests\Commands;

use Insight\Core\CommandBus;
use Insight\ProductRequests\Search\ProductRequestSearchIndex;
use Insight\Search\AlgoliaSearch;
use Laracasts\Commander\CommandHandler;

/**
 * Class UpdateProductRequestIndexCommandHandler
 * @package Insight\ProductRequests\Commands
 */
class UpdateProductRequestIndexCommandHandler implements CommandHandler
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
        $productRequests = $command->productRequests;

        $client = new AlgoliaSearch;
        $index = new ProductRequestSearchIndex($client);
        $index->update($productRequests);
    }

}
