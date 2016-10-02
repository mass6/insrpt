<?php namespace Insight\Portal\Products;

use Illuminate\Support\Facades\Log;
use Insight\Portal\Products\Events\ProductsWereDeleted;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\EventGenerator;
use Laracasts\Commander\Events\DispatchableTrait;
use Insight\Portal\Products\Events\ProductsWereUpdated;

/**
 * Insight Client Management Portal:
 * Date: 8/10/14
 * Time: 3:24 PM
 */

class UpdateProductsCommandHandler implements CommandHandler
{
    use EventGenerator, DispatchableTrait;

    /**
     * @var ProductRepository
     */
    private $product;

    private $productsWithErrors = [];

    private $deletedProducts = [];

    public $changeLog;

    public $customer;

    public $website;

    public function __construct()
{
    $this->product = new ProductRepository;
}

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $added = 0;
        $deleted = 0;
        $updated = 0;
        $this->customer = $command->customer->name;
        $this->website = $command->website;

        $local = $command->localProducts;

        // remove the 'id' field from local products array for sake of comparison
        $localProducts = [];
        foreach ($local as $product)
        {
            unset($product['id']);
            $product['entity'] = (string)$product['entity_id'];
            $localProducts[] = $product;
        }
        $portalProducts = $command->portalProducts;

        // get local entity_id's for comparison
        $localIds = [];
        foreach ($localProducts as $product){
            $localIds[] = $product['entity_id'];
        }
        $portalIds = [];
        foreach ($portalProducts as $product){
            $portalIds[] = $product['entity_id'];
        }
//        Log::info($localIds);
//        Log::info($portalIds);

        // Add products
        $toBeAddedIds = array_diff($portalIds, $localIds);

        $numToBeAdded = count($toBeAddedIds);
        $productsToBeAdded = [];

        foreach ($portalProducts as $product)
        {
            if ( in_array($product['entity_id'], $toBeAddedIds)){
                $productsToBeAdded[] = $product;
            }
        }
        //Log::info($productsToBeAdded);
        $added = $this->addProducts($productsToBeAdded);

        // Delete Products
        $toBeDeletedIds = array_diff($localIds, $portalIds);
        $numToBeDeleted = count($toBeDeletedIds);
        $productsToBeDeleted = [];
        foreach ($localProducts as $product)
        {
            if ( in_array($product['entity_id'], $toBeDeletedIds)){
                $productsToBeDeleted[] = $product;
            }
        }
        //Log::info($productsToBeDeleted);
        $deleted = $this->deleteProducts($productsToBeDeleted);
        if ($deleted) {
            $this->raise(new ProductsWereDeleted($this->deletedProducts));
        }

        // Verify Products
        $toBeVerifiedIds = array_intersect($localIds,$portalIds);
        $numToBeVerified = count($toBeVerifiedIds);
        //Log::info($toBeVerifiedIds);


        $localProducts = $this->productsToCompare($localProducts, $toBeVerifiedIds);
        $portalProducts = $this->productsToCompare($portalProducts, $toBeVerifiedIds);
        $productUpdates = $this->compareProducts($localProducts, $portalProducts);
        $numToBeUpdated = count($productUpdates);
        // process the product changes required
        if ($productUpdates){
            $updated = $this->updateProducts($productUpdates);
        }

        if ($this->changeLog){
            Log::info($this->changeLog);
            $this->raise(new ProductsWereUpdated($this->changeLog));
            $this->dispatchEventsFor($this);

            if ($this->productsWithErrors) {
                Log::info(count($this->productsWithErrors) . " products had errors and could not be updated for {$this->customer}.");
                Log::info($this->productsWithErrors);
            }

        } else {
            if ($this->productsWithErrors) {
                Log::info(count($this->productsWithErrors) . " products had errors and could not be updated for {$this->customer}.");
                Log::info($this->productsWithErrors);
            }
            else {
                Log::info("All products up to date for {$this->customer}. No changes to be made.");
            }

        }

        return "To be updated: {$numToBeUpdated}  To be added: {$numToBeAdded}  To be deleted: {$numToBeDeleted} \r\n" .
         "Actual updated: {$updated}  Actual added: {$added}  Actual deleted: {$deleted} \r\n" .
         "Errors: " . count($this->productsWithErrors) . " \r\n";



    }

    public function addProducts($products)
    {
        $added = 0;
        foreach ($products as $product)
        {
            $newProduct = $this->product->addProduct($product);
            if ($newProduct){
                $added++;
                $this->changeLog[$product['website']]['Added Products'][] = $product['sku'] . '(' . $product['bp_product_code'] . ') - ' . $product['name'];
            } else {
                $this->productsWithErrors[] = $product;
            }
        }
        return $added;
    }

    public function deleteProducts($products)
    {
        $deleted = 0;
        foreach ($products as $product)
        {
            $deletedProduct = $this->product->deleteProduct($product, $this->website);
            if ($deletedProduct){
                $this->deletedProducts[] = $deletedProduct;
                $deleted++;
                $this->changeLog[$product['website']]['Deleted Products'][] = $product['sku'] . '(' . $product['bp_product_code'] . ') - ' . $product['name'];
            }
        }
        return $deleted;
    }

    public function productsToCompare($products, $ids )
    {
        $array = [];
        foreach ($products as $product)
        {
            if (in_array($product['entity_id'], $ids)){
                $array[$product['entity_id']] = $product;
            }
        }
        asort($array);
        return $array;
    }

    public function compareProducts($localProducts, $portalProducts)
    {
        $changes = false;

        $productUpdates = [];
        foreach ($localProducts as $product)
        {
            $portalProduct = $portalProducts[$product['entity_id']];

            $columnUpdates = [];
            $i = 0;
            // Begin column comparison
            foreach ($portalProduct as $key => $val)
            {
                if ($val != $product[$key]){
                    $differences = ucwords($key) . ' changed from "' . $product[$key] . '" to "' . $val . '"';
                    $columnUpdates[$i]['entity_id'] = $portalProduct['entity_id'];
                    $columnUpdates[$i]['sku'] = $portalProduct['sku'];
                    $columnUpdates[$i]['bp_product_code'] = $portalProduct['bp_product_code'];
                    $columnUpdates[$i]['name'] = $portalProduct['name'];
                    $columnUpdates[$i]['type_id'] = $portalProduct['type_id'];
                    $columnUpdates[$i]['website'] = $portalProduct['website'];
                    $columnUpdates[$i]['status'] = $portalProduct['status'];
                    $columnUpdates[$i]['category'] = $portalProduct['category'];
                    $columnUpdates[$i]['supplier'] = $portalProduct['supplier'];
                    $columnUpdates[$i]['supplier_id'] = $portalProduct['supplier_id'];
                    $columnUpdates[$i]['url_key'] = $portalProduct['url_key'];
                    $columnUpdates[$i]['image'] = $portalProduct['image'];
                    $columnUpdates[$i]['thumbnail'] = $portalProduct['thumbnail'];
                    $columnUpdates[$i]['column'] = $key;
                    $columnUpdates[$i]['value'] = $val;
                    $columnUpdates[$i]['description'] = $differences;
                    $columnUpdates[$i]['website'] = $portalProduct['website'];

                    $i++;
                    $changes = true;
                }
                //$differences[] = 'Comparing ' . $key . ' = ' . $val . ' with ' . $product[$key];
            }
            if ($columnUpdates)
                $productUpdates[$product['entity_id']] = $columnUpdates;
            //$productUpdates[$product['entity_id']]['descriptions'] = $differences;
        }
        // Updates to be made
        //Log::info($productUpdates);

        return $productUpdates;

    }

    public function updateProducts($productUpdates)
    {
        $updated = 0;
        foreach ($productUpdates as $entityId => $columnUpdates)
        {
            //Log::info($columnUpdates);
            $updates = $this->product->updateColumns($entityId, $columnUpdates, $this->website);
            if ($updates)
            {
                $this->changeLog[$columnUpdates[0]['website']]['Updated Products'][$columnUpdates[0]['sku']
                . '(' . $columnUpdates[0]['bp_product_code'] . ') - ' . $columnUpdates[0]['name']] = $updates;
                $updated++;
            }
            //Log::info($product);
            //Log::info($columnUpdates);
        }
        return $updated;
    }
}