<?php

namespace Insight\Portal\Products\Search;

use Insight\Portal\Products\Product;
use Insight\Search\AlgoliaSearch;

class ProductSearchIndex
{
    const INDEX_NAME = 'products';
    const SITE_OWNER_CODE = '36s';

    private $client;


    public function __construct(AlgoliaSearch $client)
    {
        $this->client = $client;
        $this->client->setIndex(getenv('PRODUCTS_INDEX') ?: self::INDEX_NAME);
    }

    public function buildInitialIndex()
    {
        $products = Product::all();
        $this->client->saveMany($products);
        $this->setSettings();
    }

    public function add(Product $product)
    {
        $this->client->save($product);
    }

    public function addMany($products)
    {
        $this->client->saveMany($products);
    }

    public function update(Product $product)
    {
        $this->add($product);
    }

    public function updateMany($products)
    {
        $this->addMany($products);
    }

    public function updateIndex()
    {
        $products = Product::all();
        $this->saveMany($products);
    }

    public function delete(Product $product)
    {
        $this->client->delete($product);
    }

    public function deleteMany($products)
    {
        $this->client->deleteMany($products);
    }

    protected function setSettings()
    {
        $this->client->setSettings([
            "attributesToIndex" => ["name", "customer", "sku", "uom", "bp_product_code"],
            "attributesForFaceting" => ["customer"]
        ]);
    }

}
