<?php namespace Insight\ProductDefinitions;
use Illuminate\Support\Facades\Config;
use Insight\Portal\Products\Product;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;
use Insight\ProductDefinitions\ProductDefinitionRepository;
use \Log;
/**
 * Insight Client Management Portal:
 * Date: 11/30/14
 * Time: 11:48 AM
 */

class NewFormatCommandHandler implements CommandHandler
{
    use DispatchableTrait;

    /**
     * @var ProductDefinitionRepository
     */
    private $productDefinitionRepository;

    public function __construct(ProductDefinitionRepository $productDefinitionRepository)
    {
        $this->productDefinitionRepository = $productDefinitionRepository;
    }

    public function handle($command)
    {
        $site_owner_id = settings('site_owner');
        switch ($command->filter){
            case "all":
                if($command->customerId != $site_owner_id){
                    $products =  $this->productDefinitionRepository->getAllByCustomer($command->customerId);
                } else {
                    $products =  $this->productDefinitionRepository->getAllByAdmin();
                }

                break;
            case "completed":
                if($command->customerId != $site_owner_id){
                    $products =  $this->productDefinitionRepository->getCompletedByCustomer($command->customerId);
                } else {
                    $products =  $this->productDefinitionRepository->getCompletedByAdmin();
                }

                break;
            default:
                return false;

        }

        //dd(json_decode($products[0]->attributes));

        $header = [
            'sku',
            'name',
            'category_ids',
            'price',
            'uom',
            'short_description',
            'description',
            'manufacturing_brand',
            'harmonized_system_code',
            'barcode_number_case',
            'barcode_number_individual',
            'country_of_manufacture',
            'lead_time',
            'ingredients',
            'energy_kcal',
            'energy_kj',
            'fat',
            'saturates',
            'carbohydrates',
            'sugars',
            'protein',
            'salt',
            'calories_from_fat',
            'total_fat',
            'trans_fat',
            'cholesterol',
            'vitamin_a',
            'vitamin_c',
            'calcium',
            'iron',
            'allergens',
            'halal',
            'packing_type',
            'shelf_life',
            'storage_condition',
            'case_length',
            'case_width',
            'case_depth',
            'cases_per_pallet',
            'cases_per_pallet_row',
            'cases_per_container',
            'cases_per_container_loose',
            'weight_case_net',
            'weight_case_gross',
            'weight_individual_net',
            'weight_individual_gross',
            'weight_individual_drain',
            'websites',
            'status',
            'type',
            'attribute_set',
            'visibility'
        ];
        $data[] = $header;

        foreach ($products as $product)
        {
            $allergens = array();
            $attributes = json_decode($product->attributes);
            if(isset($attributes->{'Allergens'})){
                foreach($attributes->{'Allergens'} as $key => $val){
                    array_push($allergens,ucfirst($val));
                }
            }
            $data[] = [
                $product->code,
                $product->name,
                $product->category,
                $product->price,
                $product->uom,
                $product->short_description,
                $product->description,
                isset($attributes->{'Brand'}) ? $attributes->{'Brand'} : '',
                isset($attributes->{'HS Code'}) ? $attributes->{'HS Code'} : '',
                isset($attributes->{'Barcode Number Case'}) ? $attributes->{'Barcode Number Case'} : '',
                isset($attributes->{'Barcode Number Individual'}) ? $attributes->{'Barcode Number Individual'} : '',
                isset($attributes->{'Country of Manufacture'}) ? $attributes->{'Country of Manufacture'} : '',
                isset($attributes->{'Lead Time'}) ? $attributes->{'Lead Time'} : '',
                isset($attributes->{'Ingredients'}) ? $attributes->{'Ingredients'} : '',
                isset($attributes->{'Energy Kcal'}) ? $attributes->{'Energy Kcal'} : '',
                isset($attributes->{'Energy kJ'}) ? $attributes->{'Energy kJ'} : '',
                isset($attributes->{'Fat'}) ? $attributes->{'Fat'} : '',
                isset($attributes->{'Saturates'}) ? $attributes->{'Saturates'} : '',
                isset($attributes->{'Carbohydrates'}) ? $attributes->{'Carbohydrates'} : '',
                isset($attributes->{'Sugars'}) ? $attributes->{'Sugars'} : '',
                isset($attributes->{'Protein'}) ? $attributes->{'Protein'} : '',
                isset($attributes->{'Salt'}) ? $attributes->{'Salt'} : '',
                isset($attributes->{'Calories From Fat'}) ? $attributes->{'Calories From Fat'} : '',
                isset($attributes->{'Total Fat'}) ? $attributes->{'Total Fat'} : '',
                isset($attributes->{'Trans Fat'}) ? $attributes->{'Trans Fat'} : '',
                isset($attributes->{'Cholesterol'}) ? $attributes->{'Cholesterol'} : '',
                isset($attributes->{'Vitamin A'}) ? $attributes->{'Vitamin A'} : '',
                isset($attributes->{'Vitamin C'}) ? $attributes->{'Vitamin C'} : '',
                isset($attributes->{'Calcium'}) ? $attributes->{'Calcium'} : '',
                isset($attributes->{'Iron'}) ? $attributes->{'Iron'} : '',
                isset($attributes->{'Allergens'}) ? implode(', ', $allergens) : '',
                isset($attributes->{'Halal'}) ? ucfirst($attributes->{'Halal'}) : '',
                isset($attributes->{'Packing Type'}) ? $attributes->{'Packing Type'} : '',
                isset($attributes->{'Shelf Life'}) ? $attributes->{'Shelf Life'} : '',
                isset($attributes->{'Storage Condition'}) ? $attributes->{'Storage Condition'} : '',
                isset($attributes->{'Case Length'}) ? $attributes->{'Case Length'} : '',
                isset($attributes->{'Case Width'}) ? $attributes->{'Case Width'} : '',
                isset($attributes->{'Case Depth'}) ? $attributes->{'Case Depth'} : '',
                isset($attributes->{'Cases Per Pallet'}) ? $attributes->{'Cases Per Pallet'} : '',
                isset($attributes->{'Cases Per Pallet Row'}) ? $attributes->{'Cases Per Pallet Row'} : '',
                isset($attributes->{'Cases Per Container'}) ? $attributes->{'Cases Per Container'} : '',
                isset($attributes->{'Cases Per Container Loose'}) ? $attributes->{'Cases Per Container Loose'} : '',
                isset($attributes->{'Weight Case Net'}) ? $attributes->{'Weight Case Net'} : '',
                isset($attributes->{'Weight Case Gross'}) ? $attributes->{'Weight Case Gross'} : '',
                isset($attributes->{'Weight Individual Net'}) ? $attributes->{'Weight Individual Net'} : '',
                isset($attributes->{'Weight Individual Gross'}) ? $attributes->{'Weight Individual Gross'} : '',
                isset($attributes->{'Weight Individual Drain'}) ? $attributes->{'Weight Individual Drain'} : '',
                '',
                'Enable',
                'Simple',
                'Food',
                'Catalog, Search'
            ];
            if($product->code === '1101VEG003')
                Log::info($product->price);
        }
        return $data;

    }

}