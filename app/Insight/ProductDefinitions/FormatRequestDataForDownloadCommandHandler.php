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

class FormatRequestDataForDownloadCommandHandler implements CommandHandler
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
            'Customer',
            'Code',
            'Name',
            'Supplier',
            'Category',
            'UOM',
            'Price',
            'Brand',
            'HS Code',
            'Barcode Number (Case)',
            'Barcode Number (Individual)',
            'Country of Manufacture',
            'Lead Time(days)',
            'Ingredients',
            'Energy (kcal)',
            'Energy (kJ)',
            'Fat (g)',
            'Saturates (g)',
            'Carbohydrates(g)',
            'Sugars (g)',
            'Protein (g)',
            'Salt (g)',
            'Calories From Fat (g)',
            'Total Fat (g)',
            'Trans Fat (g)',
            'Cholesterol (g)',
            'Vitamin A (%)',
            'Vitamin C (%)',
            'Calcium (%)',
            'Iron (%)',
            'Allergens',
            'Halal',
            'Packaging',
            'Packing Type',
            'Shelf Life(months)',
            'Storage Condition',
            'Case Length (cm)',
            'Case Width (cm)',
            'Case Depth (cm)',
            'Cases Per Pallet',
            'Cases Per Pallet Row',
            'Cases Per Container',
            'Cases Per Container (loose)',
            'Weight Case: Net(kg)',
            'Weight Case: Gross(kg)',
            'Weight Individual: Net(kg)',
            'Weight Individual: Gross(kg)',
            'Weight Individual: Drain(kg)',
            'Short Description',
            'Full Description'
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
                   $product->customer->name,
                   $product->code,
                   $product->name,
                   $product->supplier ? $product->supplier->name : '',
                   $product->category,
                   $product->uom,
                   $product->price,
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
                   isset($attributes->{'Packaging'}) ? $attributes->{'Packaging'} : '',
                   isset($attributes->{'Packing Type'}) ? $attributes->{'Packing Type'} : '',
                   isset($attributes->{'Shelf Life'}) ? $attributes->{'Shelf Life'} : '',
                   isset($attributes->{'Shelf Life'}) ? $attributes->{'Storage Condition'} : '',
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
                   $product->short_description,
                   $product->description

               ];


            if($product->code === '1101VEG003')
                Log::info($product->price);
        }
        return $data;

    }

} 