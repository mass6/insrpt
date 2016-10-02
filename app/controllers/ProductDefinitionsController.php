<?php

use Cartalyst\Sentry\Users\Eloquent\User;
use Illuminate\Support\Facades;
use Insight\Companies\CompanyRepository;
use Insight\Core\CommandBus;
use Insight\ProductDefinitions\AddNewProductDefinitionCommand;
use Insight\ProductDefinitions\Forms\DraftProductDefinitionForm;
use Insight\ProductDefinitions\Forms\NewProductDefinitionForm;
use Insight\ProductDefinitions\Forms\ProductDefinitionFormFactory;
use Insight\ProductDefinitions\Forms\SupplierDraftProductDefinitionForm;
use Insight\ProductDefinitions\Forms\SupplierUpdateProductDefinitionForm;
use Insight\ProductDefinitions\Forms\UpdateLimitedProductDefinitionForm;
use Insight\ProductDefinitions\Forms\UpdateProductDefinitionForm;
use Insight\ProductDefinitions\ProductDefinitionRepository;
use Insight\ProductDefinitions\ProductDefinitionStatuses;
use Insight\ProductDefinitions\Attribute;
use Insight\ProductDefinitions\AttributeSet;
use Insight\ProductDefinitions\ProductImage;
use Insight\ProductDefinitions\UpdateLimitedProductDefinitionCommand;
use Insight\ProductDefinitions\NewFormatCommand;
use Insight\ProductDefinitions\FormatRequestDataForDownloadCommand;
use Insight\ProductDefinitions\UpdateProductDefinitionCommand;
use Laracasts\Utilities\JavaScript\Facades\JavaScript;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Insight\ProductDefinitions\AddNewResourcingRequestCommand;
use Insight\Settings\Setting;
use Insight\ProductDefinitions\ProductRequestStatus;
/**
 * Class ProductDefinitionsController
 */
class ProductDefinitionsController extends \BaseController {

    use CommandBus;

    /**
     * @var ProductDefinitionRepository
     */
    private $productDefinitionRepository;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;
    /**
     * @var NewProductDefinitionForm
     */
    private $newProductDefinitionForm;
    /**
     * @var User
     */
    //private $user;
    /**
     * @var UpdateProductDefinitionForm
     */
    private $updateProductDefinitionForm;
    /**
     * @var UpdateLimitedProductDefinitionForm
     */
    private $updateLimitedProductDefinitionForm;
    /**
     * @var DraftProductDefinitionForm
     */
    private $draftProductDefinitionForm;
    /**
     * @var SupplierDraftProductDefinitionForm
     */
    private $supplierDraftProductDefinitionForm;
    /**
     * @var SupplierUpdateProductDefinitionForm
     */
    private $supplierUpdateProductDefinitionForm;
    /**
     * @var ProductDefinitionFormFactory
     */
    private $productDefinitionFormFactory;


    /**
     * @param ProductDefinitionRepository $productDefinitionRepository
     * @param CompanyRepository $companyRepository
     * @param ProductDefinitionFormFactory $productDefinitionFormFactory
     * @param NewProductDefinitionForm $newProductDefinitionForm
     * @param UpdateProductDefinitionForm $updateProductDefinitionForm
     * @param DraftProductDefinitionForm $draftProductDefinitionForm
     * @param SupplierDraftProductDefinitionForm $supplierDraftProductDefinitionForm
     * @param UpdateLimitedProductDefinitionForm $updateLimitedProductDefinitionForm
     * @param SupplierUpdateProductDefinitionForm $supplierUpdateProductDefinitionForm
     */
    public function __construct(
        ProductDefinitionRepository $productDefinitionRepository,
        CompanyRepository $companyRepository,
        ProductDefinitionFormFactory $productDefinitionFormFactory,
        NewProductDefinitionForm $newProductDefinitionForm,
        UpdateProductDefinitionForm $updateProductDefinitionForm,
        DraftProductDefinitionForm $draftProductDefinitionForm,
        SupplierDraftProductDefinitionForm $supplierDraftProductDefinitionForm,
        UpdateLimitedProductDefinitionForm $updateLimitedProductDefinitionForm,
        SupplierUpdateProductDefinitionForm $supplierUpdateProductDefinitionForm
        )
    {
        $this->beforeFilter(function()
        {
            if(! Sentry::getUser()->hasAccess('cataloguing.products.edit'))
                return Redirect::home();
        });

        //$this->user = Sentry::getUser();
        $this->productDefinitionRepository = $productDefinitionRepository;
        $this->companyRepository = $companyRepository;
        $this->newProductDefinitionForm = $newProductDefinitionForm;
        $this->updateProductDefinitionForm = $updateProductDefinitionForm;
        $this->updateLimitedProductDefinitionForm = $updateLimitedProductDefinitionForm;
        $this->draftProductDefinitionForm = $draftProductDefinitionForm;
        $this->supplierDraftProductDefinitionForm = $supplierDraftProductDefinitionForm;
        $this->supplierUpdateProductDefinitionForm = $supplierUpdateProductDefinitionForm;

        parent::__construct();
        $this->productDefinitionFormFactory = $productDefinitionFormFactory;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index($company_id)
    {
        $displaying_products = array();
        $products = $this->user->hasAccess('cataloguing.products.admin')
            ? $this->productDefinitionRepository->getAll()
            : $this->productDefinitionRepository->getFiltered($this->user);
        $statuses = $this->productDefinitionRepository->getAllStatuses();
        $assignedList = $this->productDefinitionRepository->getAssignedList($products);
        $product_companies = $this->productDefinitionRepository->getAllProductRequestCompanies($products);
        $insight_company_id = Setting::where('name', 'insight_company')->pluck('value');
        if($this->user->company_id == $insight_company_id || $this->user->hasAccess('cataloguing.products.admin'))
        {
            if($company_id == 'company'){
                //At the first time the user clicks on "all requests"
                foreach($products as $key => $product){
                    if($product->company_id == $insight_company_id){
                        if($product->status == ProductRequestStatus::Closed){ // status is Closed
                            unset($products[$key]);
                        } else {
                            $displaying_products[] = $product;
                        }
                    }
                }
            } elseif($company_id === 'all'){
                //view all product requests
                foreach($products as $key => $product){
                    if($product->status == ProductRequestStatus::Closed){ // status is Closed
                        unset($products[$key]);
                    } else {
                        $displaying_products[] = $product;
                    }
                }
            } else{
                //filter by chosen company
                foreach($products as $key => $product){
                    if($product->company_id == $company_id){
                        if($product->status == ProductRequestStatus::Closed){ // status is Closed
                            unset($products[$key]);
                        } else {
                            $displaying_products[] = $product;
                        }
                    }
                }
            }
//
            return View::make('product-definitions.index', compact('displaying_products','statuses','assignedList','product_companies'));
        } else{
            //view all product requests
            foreach($products as $key => $product){
                if($product->status == ProductRequestStatus::Closed){ // status is Closed
                    unset($products[$key]);
                } else {
                    $displaying_products[] = $product;
                }
            }

            return View::make('product-definitions.index', compact('displaying_products','statuses','assignedList'));
        }
    }



    public function getQueue()
    {
        $products = $this->productDefinitionRepository->getUserQueue($this->user->id);
        $statuses = $this->productDefinitionRepository->getAllStatuses();
        return View::make('product-definitions.userqueue', compact('products','statuses'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $company_id = Request::get('company_id');
        $request_type = Request::get('request_type');

        if(Session::has('request_type')){
            //route from Add new supplier company function
            $request_type = 'add';
            Session::forget('request_type');
        }
        $company = $this->companyRepository->findById(Request::get('company_id', $this->user->company->id));
        $add_attributes = false;
        $companies = $this->companyRepository->getCustomersList();
        $suppliers = $this->companyRepository->getAssociatedSuppliersList($company);
        $customAttributes = false;
        $company_settings = $company->settings();

        //get current user groups
        $groups = array();
        foreach($this->user->getGroups() as $group){
            $groups[] = $group->name;
        }
        if($company_settings->get('productDefinitions.template')){
            $add_attributes = true;
            $customAttributes = $company_settings->get('productDefinitions.template');
        }
        return View::make('product-definitions.create', compact('company_id', 'company', 'companies','suppliers', 'customAttributes','add_attributes','groups','request_type'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $input['attributes'] = $this->parseAttributes($input);
        //$input['images'] = Input::file('images');
        //$input['attachments'] = Input::file('attachments');
        $validationForm = $this->productDefinitionFormFactory->make($input['action'],$this->user, $this->company);

        $validationForm->validate($input);
//        if($input['action'] === 'save' || $input['action'] === 'assign-to-customer')
//            $this->draftProductDefinitionForm->validate($input);
//        elseif($input['action'] === 'assign-to-supplier')
//            $this->supplierDraftProductDefinitionForm->validate($input);
//        else
//            $this->newProductDefinitionForm->validate($input);

        // temporary test


        extract($input);
        $product = $this->execute(new AddNewProductDefinitionCommand(
            $this->user, $code, $name, $this->user->id, $company_id, $category, $uom, $price, $currency, $description, $short_description,
            $attributes, $remarks, $supplier_id, $action, $image1, $image2, $image3, $image4, $attachment1, $attachment2, $attachment3, $attachment4, $attachment5
        ));

        Flash::success("Product {$product} was successfully created.");

        return Redirect::route('catalogue.product-definitions.index.company_id','all');
    }

    //Add new resourcing request
    public function addResourcingRequest(){
        $input = Input::all();
        $validationForm = $this->productDefinitionFormFactory->make($input['request_type'],$this->user, $this->company);
        $validationForm->validate($input);

        extract($input);

        $product = $this->execute(new AddNewResourcingRequestCommand(
            $this->user, $name, $category, $uom, $price, $short_description,$image1
        ));

        Flash::success("Product {$product} was successfully created.");

        return Redirect::route('catalogue.product-definitions.index.company_id','all');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $product = $this->productDefinitionRepository->findWithComments($id);
        $attributes = $product->attributes ? object_to_array(json_decode($product->attributes)) : [];

        $customAttributes = false;
        if($product->customer->settings()->get('productDefinitions.template'))
        {
            $customAttributes = $product->customer->settings()->get('productDefinitions.template');
        }
        return View::make('product-definitions.show', compact('product', 'attributes', 'customAttributes'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $product = $this->productDefinitionRepository->find($id);

        if($product->assigned_user_id !== $this->user->id && !$this->user->hasAccess('cataloguing.products.admin'))
        {
            Flash::error("Product is currently assigned to {$product->assignedTo->name()} and is locked for editing.");
            return Redirect::back();
        }


        $user = $this->user;
        $suppliers = $this->companyRepository->getAssociatedSuppliersList($product->customer);
        // should be able to delete statuses variable below
        //$statuses =  ProductDefinitionStatuses::lists('name', 'id');

        //should be able to delete below variable
        //$assignableUsersList = $this->productDefinitionRepository->getAssignableUsersList($product->customer, $product->supplier);

        // pass attributes object to view as JS object
        if(! empty($product->attributes)){
            //return $product->attributes;
            //JavaScript::put(['attributes' => true]);
            JavaScript::put(['attributes' => object_to_array(json_decode($product->attributes))]);
        }
        else
            JavaScript::put(['attributes' => false]);

        $customAttributes = false;
        if($product->customer->settings()->get('productDefinitions.template'))
        {
            $customAttributes = $product->customer->settings()->get('productDefinitions.template');
            JavaScript::put(['customAttributes' => true]);
        }
        $sourcing_request = false;
        if(strpos($product->code,'SOURCING_')!== false){
            $sourcing_request = true;
        }

        $form = $this->user->hasAccess('cataloguing.products.edit.full') ? '_form-wizard-edit' : '_form-wizard-edit-limited';
        return View::make('product-definitions.edit', compact('product','user','suppliers', 'customAttributes', 'form','sourcing_request'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        $input = Input::all();
        $product = $this->productDefinitionRepository->find($id);
        // parse the attributes from the form into an array
        if(!isset($input['action'])){
            $input['action'] = 'save';
        }
        $input['attributes'] = $this->parseAttributes($input);
        //
        $input['existingImage1'] = $product->image1->originalFilename() ? $product->image1 : null ;
        $input['existingImage2'] = $product->image2->originalFilename() ? $product->image2 : null ;
        $input['existingImage3'] = $product->image3->originalFilename() ? $product->image3 : null ;
        $input['existingImage4'] = $product->image4->originalFilename() ? $product->image4 : null ;
        $input['existingAttachment1'] = $product->attachment1->originalFilename() ? $product->attachment1 : null ;
        $input['existingAttachment2'] = $product->attachment2->originalFilename() ? $product->attachment2 : null ;
        $input['existingAttachment3'] = $product->attachment3->originalFilename() ? $product->attachment3 : null ;
        $input['existingAttachment4'] = $product->attachment4->originalFilename() ? $product->attachment4 : null ;
        $input['existingAttachment5'] = $product->attachment5->originalFilename() ? $product->attachment5 : null ;

        $validationForm = null;
        if(isset($input['sourcing_update'])){
            $validationForm = $this->productDefinitionFormFactory->make($input['sourcing_update'], $this->user, $product->customer);
        } else{
            $validationForm = $this->productDefinitionFormFactory->make($input['action'], $this->user, $product->customer);
        }

        //return get_class($validationForm);
        $validationForm->validate($input);
        extract($input);

        $product = $this->execute(new UpdateProductDefinitionCommand(
            $this->user, $product, $product->company_id, $supplier_id, $code, $name, $category, $uom, $price, $currency, $short_description, $description,
            $image1, $image2, $image3, $image4, $attachment1, $attachment2, $attachment3, $attachment4, $attachment5, $attributes, Input::get('form-type', 'limited'), $remarks, $action
        ));


        Flash::success("Product ( {$product->code} : {$product->name} ) was successfully updated.");

        if($input['action'] === 'save')
            return Redirect::back();

        return Redirect::route('catalogue.product-definitions.index.company_id','all');

    }

    public function getCompleted()
    {
        $products = $this->user->hasAccess('cataloguing.products.admin')
            ? $this->productDefinitionRepository->findCompleted(10)
            : $this->productDefinitionRepository->findCompletedAndFiltered($this->user, 10);
        $assignedList = $this->productDefinitionRepository->getAssignedList($products);
        return View::make('product-definitions.completed', compact('products','assignedList'));
    }

    public function export()
    {
        return View::make('product-definitions.export');
    }

    public function import(){
        return View::make('product-definitions.import');
    }

    public function download($filter = 'all', $format = 'csv')
    {
        $customerId = $this->user->company->id;
        $data = $this->execute(new FormatRequestDataForDownloadCommand($filter, $format, $customerId));


        //return $data;
        $sheetName = ucfirst($filter) . 'ProductRequests';


        Excel::create($sheetName . '_' . date('Ymd_g:i:s'), function($excel) use($data, $sheetName) {

            $excel->sheet($sheetName, function($sheet) use($data) {

                $sheet->fromArray($data, null, 'A1', false, false);

            });


        })->export($format);

    }
    public function downloadCSV($filter = 'all', $format = 'csv')
    {
        $customerId = $this->user->company->id;
        $data = $this->execute(new NewFormatCommand($filter, $format, $customerId));


        //return $data;
        $sheetName = ucfirst($filter) . 'ProductRequests';


        Excel::create($sheetName . '_' . date('Ymd_g:i:s'), function($excel) use($data, $sheetName) {

            $excel->sheet($sheetName, function($sheet) use($data) {

                $sheet->fromArray($data, null, 'A1', false, false);

            });


        })->export($format);

    }

    public function editAttributes(){
        if ($_FILES['import_file']['error'] == UPLOAD_ERR_OK && is_uploaded_file($_FILES['import_file']['tmp_name'])) {
            $info = pathinfo($_FILES['import_file']['name']);
            $target = 'documents/'.$info['basename'];
            move_uploaded_file( $_FILES['import_file']['tmp_name'], $target);
            $csv = $this->readCSV($target);
            if(!$csv){
                $csv_error = true;
                File::delete($target);
                return View::make('product-definitions.import',compact('csv_error'));
            }
            $count = $this->productDefinitionRepository->importAttributes($csv);
            File::delete($target);
            return View::make('product-definitions.import',compact('count'));
        }
    }

    public function readCSV($target){
        $file = file_get_contents($target);
        $data = array_map("str_getcsv", preg_split('/\r*\n+|\r+/', $file));
        $config_headers = json_decode(settings('catalog_requests.import_csv_headers'), true);
        $csv_headers = $data[0];
        $result = array();
        // check if the csv header has the same columns as the configured header
        $config_header_keys = array_keys($config_headers);
        $compare_header = array_diff($csv_headers,$config_header_keys);
        if(count($compare_header) > 0){
            Log::info('Incorrect headers: '.implode(', ',$compare_header).' in the CSV file.');
            return false;
        } else{
            $headers = array_values($config_headers);
            for ($i = 1; $i < count($data); $i++){
                $temp = array();
                $row = $data[$i];
                if (count($row) != count($headers)){
                    Log::info('Incorrect rows: ' . ($i+1) . ' in the CSV file.');
                    continue;
                }
                for ($j = 0; $j < count($headers); $j++) {
                    $key = $headers[$j];
                    if($key == 'Allergens'){
                        //Allergens are saved as an array
                        $temp[$key] = explode(',', $row[$j]);
                    } else {
                        $temp[$key] = $row[$j];
                    }
                }
                $result[] = $temp;
            }
            return $result;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    public function detachImage($productDefinitionId, $image)
    {
        $product = $this->productDefinitionRepository->find($productDefinitionId);
        $name = $product->{$image}->originalFilename();
        $product->{$image} = STAPLER_NULL;
        $product->save();
        return Response::json(['name' => $name]);
    }

    public function detachAttachment($productDefinitionId, $attachment)
    {
        $product = $this->productDefinitionRepository->find($productDefinitionId);
        $name = $product->{$attachment}->originalFilename();
        $product->{$attachment} = STAPLER_NULL;
        $product->save();
        return Response::json(['name' => $name]);
    }


    /**
     * @param $customer_id
     * @return mixed
     */
    public function getSuppliers($customer_id)
    {
        $customer = $this->companyRepository->findById($customer_id);
        $suppliers = $this->companyRepository->getAssociatedSuppliersList($customer);
        return Response::json($suppliers);
    }

    /**
     * @param $customer_id
     * @param null $supplier_id
     * @return mixed
     */
    public function getAssignableSupplierUsers($customer_id, $supplier_id = null)
    {
        $customer = $this->companyRepository->findById($customer_id);
        $supplier = $this->companyRepository->findById($supplier_id);
        $usersList = $this->productDefinitionRepository->getAssignableUsersList($customer, $supplier ? $supplier : null);

        return Response::json($usersList);
    }

    /**
     * @param $customer_id
     * @return mixed
     */
    public function getAssignableCustomerUsers($customer_id)
    {
        $customer = $this->companyRepository->findById($customer_id);
        $usersList = $this->productDefinitionRepository->getAssignableUsersList($customer);

        return Response::json($usersList);
    }

    /**
     * Iterates through the Input array and parses out the dynamic attributes, adding them to new array.
     * Then, returns a new array with sub-arrays for each attribute name / value pair.
     * @param $input
     * @return array
     */
//    private function parseAttributes($input)
//    {
//        $attributes = [];
//        if(isset($attributes)){
//            $attributeName = '';
//            foreach($input as $field => $value)
//            {
//                if(substr($field,0,14) === 'attribute-name'){
//                    $attributeName = $value; //substr($field,14,strlen($field)-14);
//                    continue;
//                }
//                if(substr($field,0,15) === 'attribute-value'){
//                    if($attributeName)
//                        $attributes[$attributeName] = $value;
//                }
//
//            }
//            return count($attributes) ? $attributes : null;
//        }
//    }
    private function parseAttributes($input)
    {
        $attributes = [];
        if(isset($attributes)){
            $attributeName = '';
            foreach($input as $field => $value)
            {
//                if(substr($field,0,14) === 'attribute-name'){
//                    $attributeName = $value; //substr($field,14,strlen($field)-14);
//                    continue;
//                }
                if(substr($field,0,15) === 'attribute-value'){
                        $attributes[$input['attribute-name-' . substr($field,16)]] = $value;
                }

            }
            return count($attributes) ? $attributes : null;
        }
    }


}
