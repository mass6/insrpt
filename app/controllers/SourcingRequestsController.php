<?php

use Carbon\Carbon;
use Cartalyst\Sentry\Facades\Laravel\Sentry;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\View;
use Insight\Companies\Company;
use Insight\Core\CommandBus;
use Insight\Sourcing\Forms\ImportSourcingRequestForm;
use Insight\Sourcing\ImportFileValidator;
use Insight\Sourcing\ImportSourcingRequestCommand;
use Insight\Users\User;
use Laracasts\Flash\Flash;
use Insight\Sourcing\SourcingRequest;
use Insight\Sourcing\Forms\NewSourcingRequestForm;
use Insight\Sourcing\AddNewSourcingRequestCommand;
use Insight\Sourcing\Forms\UpdateSourcingRequestForm;
use Insight\Sourcing\UpdateSourcingRequestCommand;
use Maatwebsite\Excel\Facades\Excel;

class SourcingRequestsController extends \BaseController
{

    use CommandBus;
    /**
     * @var NewSourcingRequestForm
     */
    private $newSourcingRequestForm;
    /**
     * @var UpdateSourcingRequestForm
     */
    private $updateSourcingRequestForm;

    protected $importFile;

    /**
     * @var ImportSourcingRequestForm
     */
    private $importSourcingRequestForm;

    /**
     * @param NewSourcingRequestForm $newSourcingRequestForm
     * @param UpdateSourcingRequestForm $updateSourcingRequestForm
     * @param ImportSourcingRequestForm $importSourcingRequestForm
     */
    public function __construct(NewSourcingRequestForm $newSourcingRequestForm,
                                UpdateSourcingRequestForm $updateSourcingRequestForm,
                                ImportSourcingRequestForm $importSourcingRequestForm
    )
    {
        $this->newSourcingRequestForm = $newSourcingRequestForm;
        $this->updateSourcingRequestForm = $updateSourcingRequestForm;
        $this->importSourcingRequestForm = $importSourcingRequestForm;

        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $sourcing_requests = SourcingRequest::with('customer', 'assignedTo')->get();

        return View::make('sourcing-requests.index', compact('sourcing_requests'));
    }

    /**
     * @return mixed
     */
    public function myRequests()
    {
        $sourcing_requests = SourcingRequest::with('customer', 'assignedTo')
            ->where('assigned_to_id', $this->user->id)
            ->get();
        $filterName = "My Sourcing Requests";

        return View::make('sourcing-requests.index', compact('sourcing_requests', 'filterName'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $customers = [null => 'Select a Customer'] + Company::whereType('customer')->lists('name', 'id');
        $statuses = SourcingRequest::$statuses;
        $assignableUsers = userList($this->getAllowedUsers($this->user->company->id), true);

        return View::make('sourcing-requests.create', compact('customers', 'statuses', 'assignableUsers'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $this->newSourcingRequestForm->validate($input);

        $request = $this->execute(new AddNewSourcingRequestCommand(
            $this->user, $input['customer_id'], $input['batch'], $input['received_on'], $input['customer_sku'],
            $input['customer_product_description'], $input['customer_price'], $input['customer_price_currency'],
            $input['customer_uom'], $input['tss_sku'], $input['tss_product_name'], $input['tss_buy_price'],
            $input['tss_buy_price_currency'], $input['tss_uom'], $input['supplier_name'], $input['tss_sell_price'],
            $input['tss_sell_price_currency'], $input['assigned_to_id'], $input['status'], $input['remarks']
        ));

        Flash::success("Sourcing Request was successfully created.");

        return Redirect::route('sourcing-requests.index');
    }


    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        return Redirect::home();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $sourcing_request = SourcingRequest::with('customer', 'assignedTo', 'createdBy', 'updatedBy')->findOrFail($id);
        $statuses = SourcingRequest::$statuses;
        $assignableUsers = userList($this->getAllowedUsers($sourcing_request->customer_id), true);

        return View::make('sourcing-requests.edit', compact('sourcing_request', 'statuses', 'assignableUsers'));
    }

    /**
     * Get the users allowed to contribute to a particular Sourcing Request
     *
     * @param $customer_id
     * @return static
     */
    protected function getAllowedUsers($customer_id)
    {
        // Find all users from same company as Sourcing Request, and all users from Site Owner company
        $usersFromCompanyAndSiteOwner = User::whereIn('company_id', [$customer_id, settings('site_owner')] )->get();
        // All users with access to Sourcing Requests
        $allUsersWithPermission = Collection::make(Sentry::findAllUsersWithAccess(array('sourcing-requests')));

        // return the intersected group of users
        return $allUsersWithPermission->intersect($usersFromCompanyAndSiteOwner);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $input = Input::all();
        $input['sourcing_request'] = $sourcingRequest = SourcingRequest::findOrFail($id);
        $input['customer_id'] = $sourcingRequest->customer->id;
        $this->updateSourcingRequestForm->validate(['id' => $id] + $input);

        $request = $this->execute(new UpdateSourcingRequestCommand(
            $sourcingRequest, $this->user, $input['batch'], $input['received_on'], $input['customer_sku'],
            $input['customer_product_description'], $input['customer_price'], $input['customer_price_currency'],
            $input['customer_uom'], $input['tss_sku'], $input['tss_product_name'], $input['tss_buy_price'],
            $input['tss_buy_price_currency'], $input['tss_uom'], $input['supplier_name'], $input['tss_sell_price'],
            $input['tss_sell_price_currency'], $input['assigned_to_id'], $input['status'], $input['reason_closed'],
            $input['remarks']
        ));

        Flash::success("Sourcing Request was successfully updated.");

        return Redirect::route('sourcing-requests.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        return Redirect::home();
    }


    /**
     * @return mixed
     */
    public function createImport()
    {
        $customers = [null => 'Select a Customer'] + Company::where('type', 'customer')->lists('name', 'id');
        $statuses = SourcingRequest::$statuses;
        $assignableUsers = SourcingRequest::assignableUsersList();

        return View::make('sourcing-requests.import', compact('customers', 'statuses', 'assignableUsers'));
    }

    /**
     * @throws \Laracasts\Validation\FormValidationException
     */
    public function confirmImport()
    {
        // validate form input
        $this->importSourcingRequestForm->validate($input = Input::all());

        // validate import file structure
        $importFileValidator = new ImportFileValidator($input, $this->user);
        $importFileValidator->validate();

        $sheetData = [];
        $sheetData['customer'] = Company::findOrFail($input['customer_id']);
        $sheetData['received_on'] = empty($input['received_on']) ? Carbon::today()->format('d-m-Y') : $input['received_on'];
        $sheetData['batch'] = empty($input['batch']) ? $this->generateBatchName($this->user) : $input['batch'];
        $sheetData['status'] = empty($input['status']) ? 'ASS' : $input['status'];
        $sheetData['statusName'] = empty($input['status']) ? SourcingRequest::$statuses['ASS'] : SourcingRequest::$statuses[$input['status']];
        $sheetData['assigned_to_id'] = empty($input['assigned_to_id']) ? $this->user->id : $input['assigned_to_id'];
        $sheetData['assignToName'] = empty($input['assigned_to_id']) ? $this->user->name() : User::findOrFail($input['assigned_to_id'])->name();
        $sheetData['numRequests'] = $importFileValidator->numRequests();

        Session::flash('importfile', $importFileValidator->getSpreadsheet());
        Session::flash('sheetData' , $sheetData);

        return View::make('sourcing-requests.import-confirm', compact('sheetData'));



        // create new sourcing requests


        // validate input
//        $importfile = Input::hasFile('importfile') ? Input::file('importfile') : null;
//        $customer_id = Input::get('customer_id');
//        $batch = Input::get('batch');
//        $batch = empty($batch) ? $this->generateBatchName($this->user) : $batch;
//        $received_on = Input::get('received_on');
//        $received_on = empty($received_on) ? Carbon::today()->format('Y-m-d') : $received_on;




        // validate data
//        $customer_id = Input::get('customer_id');
//        $batch = Input::get('batch');
//        $batch = empty($batch) ? $this->generateBatchName($this->user) : $batch;
//        $received_on = Input::get('received_on');
//        $received_on = empty($received_on) ? Carbon::today()->format('Y-m-d') : $received_on;
//
//        $errors = [];
//        $errorMessages = '';
//        $rowNum = 0;
//        foreach ($sheet as $row) {
//            $rowNum ++;
//            $validator = Validator::make(
//                [
//                    'customer_sku'                 => $row->customer_sku,
//                    'customer_product_description' => $row->customer_product_description,
//                    'customer_price'               => $row->customer_price,
//                    'customer_price_currency'      => $row->customer_price_currency,
//                    'customer_uom'                 => $row->customer_uom,
//                ],
//                [
//                    'customer_sku'                 => 'unique:sourcing_requests,customer_sku|max:120',
//                    'customer_product_description' => 'required|max:256',
//                    'customer_price'               => 'numeric',
//                    'customer_price_currency'      => 'required_with:customer_price|alpha|max:3',
//                    'customer_uom'                 => 'max:256',
//                ]
//            );
//
//            if ($validator->fails()) {
//                $errorString = '';
//                foreach ($validator->messages()->all() as $message) {
//                    $errorString .= $message . "<br/>";
//                }
//                $errorString .= "<br/>";
//                $errors['Product Item_' . $rowNum] = $validator->messages();
//                $errorMessages .= "Product Item_{$rowNum}:<br/>" . $errorString;
//            }
//
//
//        }
//
//        if ($errors) {
////            dd($errorMessages);
//            Flash::error($errorMessages);
//
//            return Redirect::back()->withInput();
//        }
//
//        Flash::success("File was good.");
//
//        return Redirect::back()->withInput();


        // create records


    }

    /**
     * @return mixed
     */
    public function processImport()
    {
        $importfile = Session::get('importfile');
        extract(Session::get('sheetData'));

//        dd($received_on);
        $sourcingRequests = $this->execute(new ImportSourcingRequestCommand(
            $customer->id, $received_on, $batch, $status, $assigned_to_id, $importfile, $this->user
        ));


        Flash::success(count($sourcingRequests) . " Sourcing Requests were successfully imported.");

        return Redirect::route('sourcing-requests.index');

    }

    /**
     * @param $user
     * @return string
     */
    private function generateBatchName($user)
    {
        return $user->first_name . '-' . Carbon::now(getenv('APP_TIMEZONE'))->format('ymdhi');
    }

}
