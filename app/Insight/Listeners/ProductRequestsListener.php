<?php

namespace Insight\Listeners;

use Insight\ProductRequests\Commands\UpdateProductRequestIndexCommand;
use Insight\ProductRequests\Commands\UpdateProductRequestIndexCommandHandler;
use Insight\ProductRequests\Events\ProductRequestListWasUploaded;
use Insight\ProductRequests\Events\ProductRequestWasCreated;
use Insight\ProductRequests\Events\ProductRequestWasUpdated;

/**
 * Class ProductRequestsListener
 * @package Insight\Listeners
 */
class ProductRequestsListener extends EventListener
{


    /**
     * @param ProductRequestWasCreated $event
     */
    public function whenProductRequestWasCreated(ProductRequestWasCreated $event)
    {
        $this->updateSearchIndex($event->productRequest);
    }

    /**
     * @param ProductRequestWasUpdated $event
     */
    public function whenProductRequestWasUpdated(ProductRequestWasUpdated $event)
    {
        $this->updateSearchIndex($event->productRequest);
    }

    private function updateSearchIndex($productRequest)
    {
        $command = new UpdateProductRequestIndexCommand($productRequest);
        $handler = new UpdateProductRequestIndexCommandHandler;
        $handler->handle($command);
    }

    /**
     * @param ProductRequestListWasUploaded $event
     */
    public function whenProductRequestListWasUploaded(ProductRequestListWasUploaded $event)
    {
        $productRequestList = $event->productRequestList;
        $productRequests = [];
        foreach ($productRequestList->productRequests as $productRequest) {
            $productRequests[] = $productRequest->toArray();
        }
        $this->updateSearchIndex($productRequests);
    }

}