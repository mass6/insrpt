<?php namespace Insight\Portal\Products;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Insight Client Management Portal:
 * Date: 8/13/14
 * Time: 10:02 AM
 */
class ProductRepository
{

    public function getAll()
    {
        if ($products = File::exists(storage_path() . '/cache/all_products.txt')) {
            return File::get(storage_path() . '/cache/all_products.txt');
        }
        $products =  Product::all();
        File::put(storage_path() . '/cache/all_products.txt', $products);

        return $products;
    }

    public function getCustomerProducts($website = null)
    {
        if ($website) {
            return Product::where('website', $website)->get();
        }
        return $this->getAll();
    }

    public function addProduct($product)
    {
        try {
            $productAdded = Product::create($product);
            return $productAdded;
        }
        catch (\Exception $e) {
            return false;
        }
    }

    public function deleteProduct($productId, $website)
    {
        $product = Product::where('entity_id', $productId['entity_id'])
            ->where('website', $website)
            ->first();
        if ($product)
        {
            $product->delete();

            return $product;
        }
    }

    public function updateColumns($id, $columnUpdates, $website)
    {
        $product = Product::where('entity_id', $id)
            ->where('website', $website)
            ->first();

        if ($product)
        {

            $changes = [];
            foreach ($columnUpdates as $update)
            {
                $product->$update['column'] = $update['value'];
                $changes[] = $update['description'];
            }
            if ($product->save())
            {
                return $changes;
            }
            return false;

        }
        return false;

    }

} 