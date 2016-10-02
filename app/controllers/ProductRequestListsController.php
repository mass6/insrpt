<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\View;
use Insight\Companies\Company;
use Insight\Core\CommandBus;
use Insight\ProductRequests\Commands\UploadProductRequestListCommand;
use Insight\ProductRequests\Forms\UploadProductRequestListForm;
use Insight\ProductRequests\ProductRequestListRepository;
use Insight\ProductRequests\Validators\UploadFileValidator;
use Insight\Users\User;
use Laracasts\Flash\Flash;
use Maatwebsite\Excel\Facades\Excel;

/**
 * Class ProductRequestListsController
 */
class ProductRequestListsController extends \BaseController
{

    use CommandBus;

    /**
     * @var UploadProductRequestForm
     */
    private $uploadProductRequestListForm;
    /**
     * @var ProductRequestListRepository
     */
    private $productRequestListRepository;

    /**
     * @param ProductRequestListRepository $productRequestListRepository
     * @param UploadProductRequestListForm $uploadProductRequestListForm
     */
    public function __construct(ProductRequestListRepository $productRequestListRepository,
                                UploadProductRequestListForm $uploadProductRequestListForm)
    {
        @parent::__construct();
        $this->uploadProductRequestListForm = $uploadProductRequestListForm;
        $this->productRequestListRepository = $productRequestListRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $product_request_lists = $this->productRequestListRepository->all();

        return View::make('product-request-lists.index', compact('product_request_lists'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {

        $companies = [$this->user->company->id => $this->user->company->name];
        $requesterList = userList($this->company->users);

        if ($this->user->hasAccess('product-requests.create-on-behalf')) {

            if (isSiteOwner($this->user)) {
                $companies = Company::lists('name', 'id');
            }

            if (Request::ajax()) {

                $companyId = Input::get('company');
                if ($companyId) {
                    $company = Company::with('users')->where('id', $companyId)->first();
                    $requesterList = [];
                    foreach ($company->users as $user) {
                        $requesterList[] = ['id' => $user->id, 'name' => $user->name()];
                    }

                    return Response::json($requesterList,200);
                }

                return Response::json(['data' => null], 400);
            }

        }

        return View::make('product-request-lists.upload', compact('companies', 'requesterList'));
    }


    /**
     * @return mixed
     * @throws \Laracasts\Validation\FormValidationException
     */
    public function confirm()
    {
        // validate form input
        $formData = $input = Input::all();
        $formData['company_id'] = Input::get('company_id', $this->user->company->id);
        $requester = User::where('company_id', $formData['company_id'])->where('id', Input::get('requested_by_id', $this->user->id))->first();
        $this->uploadProductRequestListForm->validate($formData);

        // validate import file structure
        $importFileValidator = new UploadFileValidator($input, $requester);
        $importFileValidator->validate();

        $uploadData = [];
        $uploadData['name'] = $input['name'];
        $uploadData['numRequests'] = $importFileValidator->numRequests();
        $uploadData['requesterName'] = $requester->name();
        $uploadData['referenceFields'] = $importFileValidator->getReferenceFields();

        Session::flash('uploadFile', $importFileValidator->getSpreadsheet());
        Session::flash('uploadData', $uploadData);
        Session::flash('company_id', $requester->company->id);
        Session::flash('requested_by_id', $requester->id);

        return View::make('product-request-lists.confirm', compact('uploadData'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $uploadFile = Session::get('uploadFile');
        $name = Session::get('uploadData')['name'];
        $referenceFields = Session::get('uploadData')['referenceFields'];
        $transition = key(Input::get('transition'));
        $company_id = Session::get('company_id', $this->user->company->id);
        $requested_by_id = Session::get('requested_by_id', $this->user->id);
        $requester = User::findOrFail($requested_by_id);

        $productRequestList = $this->execute(new UploadProductRequestListCommand($this->user, $requester, $company_id, $name, $uploadFile, $referenceFields, $transition));

        Flash::success(count($productRequestList->productRequests) . " Product Requests were successfully uploaded.");

        return Redirect::route('product-request-lists.show', $productRequestList->id);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $product_request_list = $this->productRequestListRepository->findById($id);

        return View::make('product-request-lists.show', compact('product_request_list'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }

    /**
     *
     */
    public function sample()
    {
        $settings = $this->user->company->settings();

        $data = [
            [
                'Product_Description'      => 'Product Alpha',
                'Category'                 => $settings->get('product-requests.procurement-categories', ['Default'])[0],
                'UOM'                      => 'Each',
                'First_Time_Order_Quantity' => '10',
                'Purchase_Recurrence'      => 'Monthly',
                'Volume'                   => 15,
                'Current_SKU'              => 'ABX-12345',
                'Current_Price'            => '45.50',
                'Current_Price_Currency'   => 'AED',
                'Current_Supplier'         => 'Acme Supply',
                'Current_Supplier_Contact' => "1234 Any Street\r\nAnywhere, UAE, 35456\r\n(555)555-1212\r\namce@test.com",
            ],
        ];

        if ($settings->get('product-requests.references.enabled')) {
            for ($r = 1; $r <= 4; $r ++) {
                if ($settings->get("product-requests.reference{$r}.enabled")) {
                    $label = $settings->get("product-requests.reference{$r}.label", "Reference{$r}");
                    $name = $label !== '' ? trim(str_replace(' ', '_', $label)) : "Reference{$r}";
                    $data[0][$name] = '{' . strtolower($label) . '}';
                }
            }
        }

        $data[0]['Remarks'] = 'some comments about this request.';

        Excel::create('SampleProductRequestUploadFile', function ($excel) use ($data) {

            $excel->sheet('SampleData', function ($sheet) use ($data) {

                $sheet->fromArray($data, null, 'A1', false);

            });

        })->export('xlsx');


    }

}
