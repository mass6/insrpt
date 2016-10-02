<?php

namespace Insight\Listeners;

use Illuminate\Cache\Repository;
use Illuminate\Support\Facades\File;
use Insight\Portal\Products\Events\ProductsWereUpdated;
use Insight\Portal\Products\ProductRepository;

class CacheUpdater extends EventListener
{

    /**
     * @var Repository
     */
    private $cache;

    /**
     * @var ProductRepository
     */
    private $productRepository;


    /**
     * CacheUpdater constructor.
     *
     * @param Repository        $cache
     * @param ProductRepository $productRepository
     */
    public function __construct(Repository $cache, ProductRepository $productRepository)
    {
        $this->cache = $cache;
        $this->productRepository = $productRepository;
    }


    /**
     * @param ProductsWereUpdated $event
     *
     */
    public function whenProductsWereUpdated(ProductsWereUpdated $event)
    {
        if (File::exists(storage_path() . '/cache/all_products.txt')) {
            File::delete(storage_path() . '/cache/all_products.txt');
        }
        $this->productRepository->getAll();
    }

}
