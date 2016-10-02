<?php

namespace Insight\Listeners;

use Illuminate\Support\Facades\Log;
use Insight\Portal\Products\Events\ProductsWereDeleted;
use Insight\Portal\Products\Events\ProductsWereUpdated;
use Insight\Portal\Products\Product;
use Insight\Portal\Products\Search\ProductSearchIndex;

class PortalDataListener extends EventListener
{

    /**
     * @var ProductSearchIndex
     */
    private $searchClient;


    public function __construct(ProductSearchIndex $searchClient)
    {
        $this->searchClient = $searchClient;
    }


    /**
     * @param ProductsWereUpdated $event
     *
     */
    public function whenProductsWereUpdated(ProductsWereUpdated $event)
    {
        if (! settings('live_search.enabled')) {
            return;
        }
        $customer = key($event->changeLog);
        $changelog = $event->changeLog[$customer];

        if (isset($changelog['Added Products'])) {
            $this->searchClient->addMany($this->getProductsFromString($customer, $changelog['Added Products']));
            Log::info(['Products Added To Search Index' => $changelog['Added Products']]);
        }
        if (isset($changelog['Updated Products'])) {
            $this->searchClient->updateMany($this->getProductsFromString($customer, $changelog['Updated Products']));
            Log::info(['Products Updated on Search Index' => $changelog['Updated Products']]);
        }
    }

    public function whenProductsWereDeleted(ProductsWereDeleted $event)
    {
        if (! settings('live_search.enabled')) {
            return;
        }
        $this->searchClient->deleteMany($event->products);
        Log::info(['Products Removed from Search Index' => $event->products]);
    }

    protected function getProductsFromString($customer, $entries)
    {
        $products = [];
        foreach ($entries as $entry)
        {
            $product = Product::where('customer', $customer)->where('sku', $this->parseProductSku($entry))->first();
            if ($product) {
                $products[] = $product;
            }
        }

        return $products;
    }

    protected function parseProductSku($string)
    {
        return substr($string,0,strpos($string,'('));
    }

}
