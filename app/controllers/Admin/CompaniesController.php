<?php namespace Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Insight\Core\CommandBus;
use Insight\Companies\AddNewCompanyCommand;
use Insight\Companies\CompanyRepository;
use Insight\Companies\DeleteCompanyCommand;
use Insight\Companies\Forms\CompanyForm;
use Insight\Companies\Forms\SupplierForm;
use Insight\Companies\UpdateCompanyCommand;
use Insight\Portal\Orders\DailyOrderReport;
use Insight\Users\User;
use Laracasts\Flash\Flash;
use Laracasts\Utilities\JavaScript\Facades\JavaScript;
use View, Input, Redirect;
use Insight\Portal\Connections\Webservices;
use Insight\Users\AddNewUserCommand;
use Insight\Companies\AddNewSupplierCommand;

class CompaniesController extends AdminBaseController {

    use CommandBus;
    /**
     * @var CompanyRepository
     */
    private $companyRepository;
    /**
     * @var CompanyForm
     */
    private $companyForm;
    /**
     * @var SupplierForm
     */
    private $supplierForm;


    public function __construct(CompanyRepository $companyRepository, CompanyForm $companyForm, SupplierForm $supplierForm)
    {
        $this->companyRepository = $companyRepository;
        $this->companyForm = $companyForm;
        $this->supplierForm = $supplierForm;
        parent::__construct();
    }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
        $companies = $this->companyRepository->getAll();

        return View::make('admin.companies.index', compact('companies'));
	}

    //get the Customer Groups
    public function getCustomerGroups(){
        $result = array();
        $webservice = new Webservices();
        $customerGroups = $webservice->getCustomerGroups();
        foreach($customerGroups as $group){
            $result[$group->customer_group_id] = $group->customer_group_code;
        }
        return $result;
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
        $customerGroups = $this->getCustomerGroups();
        $settings = $this->setOrderReportConfig();
        $reportFieldSelectionOptions = $this->getReportFieldSelectionOptions($settings);
        return View::make('admin.companies.create',compact('customerGroups', 'settings', 'reportFieldSelectionOptions'));
    }


    public function createSupplier($company_id){
        $is_create_supplier = true;
        return View::make('admin.companies.create_supplier',compact('company_id','is_create_supplier'));
    }

    //add new supplier company and it's primary contact
    public function addSupplier(){
        $input = Input::all();
        $this->supplierForm->validate($input);
        extract(Input::only('name', 'type', 'notes','primary_contact_user_id','magento_customer_group_id',
            'address1_description', 'address1_body','address2_description', 'address2_body',
            'address3_description', 'address3_body','address4_description', 'address4_body'
        ));
        $magento_customer_group_id = 0; //customer group is NOT LOGGED IN
        $customer_id = $input['company_id'];
        $new_company_id = $this->execute(new AddNewSupplierCommand($name, $type = 'supplier',
            $notes,$primary_contact_user_id,$magento_customer_group_id,$customer_id,
            $address1_description, $address1_body, $address2_description, $address2_body,
            $address3_description, $address3_body, $address4_description, $address4_body));
        if($new_company_id){
            //add primary contact
            $email = $input['email'];
            $password = chr( mt_rand( ord( 'a' ) ,ord( 'z' ) ) ) .substr( md5( time( ) ) ,1 );
            $company_id = $new_company_id;
            $is_primary = true;
            $first_name = $input['first_name'];
            $last_name = $input['last_name'];
            $send_email = '';
            $permissionsAllowed = null;
            $permissionsDenied = null;
            $this->execute(new AddNewUserCommand($first_name, $last_name, $email, $password, $company_id,
                $send_email, $permissionsAllowed, $permissionsDenied, $groups = [], $is_primary));

        }
        Flash::message('Supplier company was successfully created.');
        //set $request_type value to return the add new request page ;
        return Redirect::route('catalogue.product-definitions.create', ['company_id' => $customer_id])->with('request_type', true)->with('selectedSupplier', $new_company_id);
    }
	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
        $input = Input::all();
        $settings = $input['settings'];
        $categories = parseMultilineStringIntoArray($settings['product-requests']['procurement-categories']);
        $settings['product-requests']['procurement-categories'] = $categories;

        $this->companyForm->validate($input);

        extract(Input::only('name', 'type', 'notes','primary_contact_user_id','magento_customer_group_id',
            'address1_description', 'address1_body','address2_description', 'address2_body',
            'address3_description', 'address3_body','address4_description', 'address4_body'
        ));


        $this->execute(new AddNewCompanyCommand($name, $type, $notes,$primary_contact_user_id,$magento_customer_group_id,
            $address1_description, $address1_body, $address2_description, $address2_body,
            $address3_description, $address3_body, $address4_description, $address4_body, $settings));

        Flash::message('Company was successfully created.');
        return Redirect::route('admin.companies.index');
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		$company = $this->companyRepository->findById($id);
        foreach($company->customers as $customer)
        {
            var_dump($customer->toArray());
        }
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
        $users = User::where('company_id',$id)->get();
        $userDropdown = array();
        $is_edit = true;
        foreach($users as $item){
            $userDropdown[$item->id] = $item->name();
        }
        $userDropdown = [null => ''] + $userDropdown;
        $customerGroups = $this->getCustomerGroups();
        $company = $this->companyRepository->findById($id);
        $company->settings = $company->settings()->all();
        $company->settings = $this->setOrderReportConfig($company->settings);
        $reportFieldSelectionOptions = $this->getReportFieldSelectionOptions($company->settings);
        if($company->type == 'customer'){
            $suppliers = $this->companyRepository->getAssociatedSuppliersList($company);
            unset($suppliers['']);
            $allSuppliers = $this->companyRepository->getSuppliersList();
            $unassignedSuppliers = array_diff_key($allSuppliers,$suppliers);
            return View::make('admin.companies.edit', compact('company','userDropdown','is_edit','unassignedSuppliers','suppliers','customerGroups', 'reportFieldSelectionOptions'));
        }
        return View::make('admin.companies.edit', compact('company','userDropdown','is_edit','customerGroups', 'reportFieldSelectionOptions'));
	}

    protected function getReportFieldSelectionOptions($settings = [])
    {

        $selected = [];
        $all = DailyOrderReport::$defaultConfiguration['field_definitions'];
        if ($settings) {
            $selected = array_get($settings, 'order_report.field_definitions', []);
        }
        $selectable = array_diff_key($all, $selected);

        return ['all' => $all, 'selected' => $selected, 'selectable' => $selectable];
    }

    protected function setOrderReportConfig($settings = [])
    {
        if (! array_get($settings, 'order_report')) {
            array_set($settings, 'order_report', DailyOrderReport::getDefaultFields());
        }

        return $settings;
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
        $input['id'] = $id;
        $settings = $input['settings'];
        $categories = parseMultilineStringIntoArray($settings['product-requests']['procurement-categories']);
        $settings['product-requests']['procurement-categories'] = $categories;

        $this->companyForm->validate($input);

        extract(Input::only('name', 'type', 'notes','primary_contact_user_id','supplier_ids','magento_customer_group_id',
            'address1_description', 'address1_body','address2_description', 'address2_body',
            'address3_description', 'address3_body','address4_description', 'address4_body'
        ));
        $this->execute(new UpdateCompanyCommand($id, $name, $type, $notes,$primary_contact_user_id,$supplier_ids,$magento_customer_group_id,
            $address1_description, $address1_body, $address2_description, $address2_body,
            $address3_description, $address3_body, $address4_description, $address4_body, $settings));

        Flash::message('Company was successfully updated.');
        return Redirect::route('admin.companies.index');
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
        $company = $this->companyRepository->findById($id);
        $delete = $this->execute(new DeleteCompanyCommand($company));
        if($delete){
            Flash::message('Company was successfully deleted.');
        }

        return Redirect::route('admin.companies.index')->with('is_deleted',$delete);
	}

    public function getFormattingPartial()
    {
        if (\Request::ajax()) {
            $key = Input::get('key');
            $field = Input::get('field');
            $type = $field['type'];
            $partial = View::make("admin.companies.partials._{$type}_formatting", ['key' => $key, 'field' => $field])->render();
            return Response::json( ['partial' => $partial ]);
        }
    }


}
