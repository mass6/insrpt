<?php namespace Insight\ProductDefinitions;
use Insight\Companies\Company;
use Insight\Mailers\ProductDefinitionsMailer;
use Log;
use Illuminate\Support\Facades\DB;
use Insight\Companies\CompanyRepository;
use Insight\ProductDefinitions\ProductRequestStatus;
/**
 * Insight Client Management Portal:
 * Date: 11/5/14
 * Time: 11:33 AM
 */

/**
 * Class ProductDefinitionRepository
 * @package Insight\ProductDefinitions
 */
class ProductDefinitionRepository
{

    /**
     * @return mixed
     */
    public function getAll()
    {
        return ProductDefinition::all();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function find($id)
    {
        return ProductDefinition::findOrFail($id);
    }

    public function getUserQueue($id)
    {
        return ProductDefinition::with('customer')
            ->with('assignedTo')
            ->with('statusName')
            ->where('assigned_user_id', $id)
            ->whereNotIn('status', [ProductRequestStatus::Approved, ProductRequestStatus::Upload, ProductRequestStatus::Closed])
            ->get();
    }
    /**
     * @param $id
     * @return mixed
     */
    public function findWithComments($id)
    {
        return ProductDefinition::with('comments')->find($id);
    }

    /**
     * @param int $num
     * @return mixed
     */
    public function getPaginated($num = 10)
    {
        return ProductDefinition::with('customer')
            ->with('supplier')
            ->with('assignedTo')
            ->with('statusName')
            ->where('status', '<>', ProductRequestStatus::Approved)
            ->orderBy('created_at', 'desc')
            ->paginate($num);
    }

    public function getAllByCustomer($company_id)
    {
        return ProductDefinition::where('company_id', $company_id)->get();
    }

    public function getCompletedByCustomer($company_id)
    {
        return ProductDefinition::where('company_id', $company_id)
            ->where('status', ProductRequestStatus::Approved)
            ->get();
    }

    public function getAllByAdmin(){
        return ProductDefinition::all();
    }

    public function getCompletedByAdmin(){
        return ProductDefinition::where('status', ProductRequestStatus::Approved)
            ->get();
    }

    public function findCompleted()
    {
        return ProductDefinition::with('customer')
            ->with('assignedTo')
            ->with('supplier')
            ->with('statusName')
            ->where('status', ProductRequestStatus::Closed)
            ->orderBy('updated_at', 'desc')->get();
    }

    public function findCompletedAndFiltered($user)
    {
        return ProductDefinition::with('customer')
            ->with('assignedTo')
            ->with('supplier')
            ->with('statusName')
            ->where('status', ProductRequestStatus::Closed)
            ->Where(function($query) use ($user)
            {
                $query->where('user_id', $user->id)
                    ->orWhere('company_id', $user->company->id);
            })
            ->orderBy('updated_at', 'desc')->get();
    }

    /**
     * @param $user
     * @return mixed
     */
    public function getFiltered($user)
    {
        return ProductDefinition::with('customer')
            ->with('assignedTo')
            ->with('supplier')
            ->with('statusName')
            ->where('status', '<>', ProductRequestStatus::Closed)
            ->where(function($query) use ($user)
            {
                $query->whereIn('assigned_user_id', $user->company->users->lists('id'))
                    ->orWhere('user_id', $user->id)
                    ->orWhere('company_id', $user->company->id);
            })
            ->orderBy('created_at', 'desc')->get();
    }

    /**
     * @param $customer
     * @param null $supplier
     * @return array
     */
    public function getAssignableUsersList($customer, $supplier = null)
    {

        // get all other users from the users's own company
        $customerUsers = $customer->users;

        // get 36S users
        $thirtySixStrat = Company::where('name', '36s')->first();
        $thirtySixStratUsers = $thirtySixStrat->users;


        // create array formatted for Select input
        $usersList = [];

        // User's Company Group
        foreach($customerUsers as $user)
        {
            $usersList[$customer->name][$user->id] = $user->name();
        }
        if($customer->name !=='36s') {
            foreach ($thirtySixStratUsers as $user) {
                if($user->hasAccess('cataloguing.products.admin')){
                    $usersList['36S'][$user->id] = $user->name();
                }
            }
        }
        // if supplier if provided, add users to usersList array
        if (isset($supplier))
        {
            if($supplier->name !=='36s'){
                // get all users from the supplier
                $supplierUsers = $supplier->users;

                foreach($supplierUsers as $user)
                {
                    $usersList[$supplier->name][$user->id] = $user->name();
                }
            }
        }

        return $usersList;
    }

    /**
     * @param AddNewProductDefinitionCommand $product
     * @return mixed
     */
    public function create(AddNewProductDefinitionCommand $product)
    {
        $newProduct = ProductDefinition::create([
            'user_id' => $product->user_id,
            'company_id' => $product->company_id,
            'code' => $product->code,
            'name' => $product->name,
            'category' => $product->category,
            'uom' => $product->uom,
            'price' => $product->price,
            'currency' => $product->currency,
            'short_description' => $product->short_description,
            'description' => $product->description,
            'image1' => $product->image1,
            'image2' => $product->image2,
            'image3' => $product->image3,
            'image4' => $product->image3,
            'attachment1' => $product->attachment1,
            'attachment2' => $product->attachment2,
            'attachment3' => $product->attachment3,
            'attachment4' => $product->attachment4,
            'attachment5' => $product->attachment5,
            'attributes' => $product->attributes,
            'remarks' => $product->remarks,
            'supplier_id' => ! empty($product->supplier_id) ? $product->supplier_id: null,
            'assigned_user_id' => $product->user_id,
            'assigned_by_id' => $product->user_id,
            'updated_by_id' => $product->user_id,
            'status' => $product->status,
        ]);
        return $newProduct;

    }

    /**
     * @param AddNewResourcingRequestCommand $product
     * @return mixed
     */
    public function add(AddNewResourcingRequestCommand $product)
    {
        $newProduct = ProductDefinition::create([
            'user_id' => $product->user_id,
            'company_id' => $product->company_id,
            'code' => $product->code,
            'name' => $product->name,
            'category' => $product->category,
            'uom' => $product->uom,
            'price' => $product->price,
            'short_description' => $product->short_description,
            'image1' => $product->image1,
            'assigned_user_id' => $product->assigned_user_id,
            'assigned_by_id' => $product->user_id,
            'updated_by_id' => $product->user_id,
            'status' => $product->status,
            'created_at' => $product->created_at,
            'supplier_id' => $product->supplier_id
        ]);
        return $newProduct;

    }

    /**
     * Used to update product when used with full edit web form
     *
     * @param ProductDefinition $productToUpdate
     * @param UpdateProductDefinitionCommand $command
     * @return mixed
     */
    public function update(ProductDefinition $productToUpdate, UpdateProductDefinitionCommand $command)
    {
        $productToUpdate->supplier_id = ! empty($command->supplier_id) ? $command->supplier_id: null;
        $productToUpdate->code = $command->code;
        $productToUpdate->name = $command->name;
        $productToUpdate->category = $command->category;
        $productToUpdate->uom = $command->uom;
        $productToUpdate->price = $command->price;
        $productToUpdate->currency = $command->currency;
        $productToUpdate->short_description = $command->short_description;
        $productToUpdate->description = $command->description;
        if (!empty($command->image1))
            $productToUpdate->image1 = $command->image1;
        if (!empty($command->image2))
            $productToUpdate->image2 = $command->image2;
        if (!empty($command->image3))
            $productToUpdate->image3 = $command->image3;
        if (!empty($command->image4))
            $productToUpdate->image4 = $command->image4;
        if (!empty($command->attachment1))
            $productToUpdate->attachment1 = $command->attachment1;
        if (!empty($command->attachment2))
            $productToUpdate->attachment2 = $command->attachment2;
        if (!empty($command->attachment3))
            $productToUpdate->attachment3 = $command->attachment3;
        if (!empty($command->attachment4))
            $productToUpdate->attachment4 = $command->attachment4;
        if (!empty($command->attachment5))
            $productToUpdate->attachment5 = $command->attachment5;
        $productToUpdate->attributes = $command->attributes;
        $productToUpdate->remarks = $command->remarks;
        $productToUpdate->status = $command->status;
        $productToUpdate->updated_by_id = $command->user->id;

        $productToUpdate->save();
        return $productToUpdate;

    }

    /**
     * Used to update product attributes, specifically
     *
     * @param $id
     * @param $attributes
     * @internal param UpdateProductDefinitionCommand $command
     * @return mixed
     */
    public function updateAttributes($id, $attributes)
    {
        $productToUpdate = $this->find($id);
        $productToUpdate->attributes = $attributes;
        $productToUpdate->save();
        return $productToUpdate;

    }

    /**
     * Used to update product when used with the limited-edit web form
     *
     * @param UpdateProductDefinitionCommand $command
     * @return mixed
     */
    public function updateLimited(UpdateProductDefinitionCommand $command)
    {
        $productToUpdate = $this->find($command->id);

        $productToUpdate->description = $command->description;
        $productToUpdate->short_description = $command->short_description;
        $productToUpdate->attributes = $command->attributes;
        $productToUpdate->remarks = $command->remarks;
        $productToUpdate->updated_by_id = $command->current_user_id;
        if($command->action !== 'save'){
            $productToUpdate->assigned_user_id = $command->assigned_user_id;
            $productToUpdate->assigned_by_id = $command->current_user_id;
        }
        $productToUpdate->status = $command->status;

        $productToUpdate->save();

        return $productToUpdate;

    }

    public function checkSKU($sku){
        $skuList = ProductDefinition::lists('code','id');
        return in_array($sku,$skuList);
    }

    public function getProductBySku($sku){
        return ProductDefinition::where('code', $sku)->first();
    }

    public function importAttributes($csv){
        $count = 0;
        foreach($csv as $value){
            $sku = $value['code'];
            unset($value['code']);
            if($sku){
                if($this->checkSKU($sku)){
                    $product = $this->getProductBySku($sku);
                    $value['Packaging'] = $product->uom;
                    $attributes = json_encode($value);
                    $product->attributes = $attributes;
                    $product->save();
                    $count +=1;
                } else{
                    Log::info('SKU ' . $sku . ' does not exist.');
                }
            }

        }
        return $count;
    }

    //get all of statuses from DB
    public function getAllStatuses(){
        $statuses = DB::table('product_definition_statuses')->get();
        $statusList = array();
        if($statuses){
            foreach($statuses as $status){
                $statusList[] = $status->name;
            }
        } else{
            Log::info('Statuses list is null.');
        }

        return $statusList;
    }

    //get assigned list
    public function getAssignedList($products){
        $assignedList = array();

        if($products){
            foreach($products as $product){
                if(isset($product->assigned_user_id)){
                    $assignedList[] = $product->assignedTo->name();
                }
            }
            //remove the duplicates names
            $assignedList = array_values(array_unique($assignedList));
        } else{
            Log::info('Product requests list is null.');
        }

        return $assignedList;
    }

    public function getAllProductRequestCompanies($products){
        $companies = array();
        $company_ids = array();
        $companyRepository = new CompanyRepository();
        if($products){
            foreach($products as $product){
                if($product->company_id){
                    if(!in_array($product->company_id,$company_ids)){
                        $company_ids[] = $product->company_id;
                    }

                }
            }
            if($company_ids){
                foreach($company_ids as $id){
                    $company = $companyRepository->findById($id);
                    $companies[] = $company;
                }
            }
        } else{
            Log::info('Product requests list is null.');
        }
        return $companies;
    }
}