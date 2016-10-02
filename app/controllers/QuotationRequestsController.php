<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Response;
use Insight\Core\CommandBus;
use Insight\ProductRequests\ProductRequest;
use Insight\Quotations\Commands\AddNewQuotationRequestCommand;
use Insight\Quotations\Commands\UpdateQuotationRequestCommand;
use Insight\Quotations\Forms\QuotationRequestForm;
use Insight\Quotations\QuotationRequest;
use Laracasts\Flash\Flash;

/**
 * Class QuotationRequestsController
 */
class QuotationRequestsController extends \BaseController
{

    use CommandBus;

    /**
     * @var QuotationRequestForm
     */
    private $quotationRequestForm;

    /**
     * @param QuotationRequestForm $quotationRequestForm
     */
    public function __construct(QuotationRequestForm $quotationRequestForm)
    {
        parent::__construct();
        $this->quotationRequestForm = $quotationRequestForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        if (Request::wantsJson()) {

            $statusLabels = QuotationRequest::getStateLabels();
            $case = '';
            foreach ($statusLabels as $state => $label) {
                $case .= "WHEN '{$state}' THEN '{$label}' ";
            }
            $query = DB::table('quotation_requests as t1')
                ->join('companies', 'companies.id', '=', 't1.company_id')
                ->join('users as u1', 'u1.id', '=', 't1.created_by_id')
                ->leftJoin('suppliers', 'suppliers.id', '=', 't1.supplier_id')
                ->leftJoin("quotations", "quotations.quotation_request_id", "=", "t1.id")
                ->select('t1.*', 'companies.name as company',
                    DB::raw('CONCAT(u1.first_name, " ", u1.last_name) AS created_by'),
                    'suppliers.name as supplier',
                    DB::raw('COUNT(quotations.id) as quotations'),
                    DB::raw('CASE t1.state ' . $case . 'END as status'),
                    DB::raw('CASE t1.sent WHEN 1 THEN "Yes" ELSE "No" END as emailed')
                )
                ->groupBy('t1.id');
            if ($this->user->company->id !== (int) settings('site_owner')) {
                $query->where('t1.company_id', $this->user->company->id);
            }
            $quotation_requests = $query->get();

            return Response::json($quotation_requests);
        }

        return View::make('quotation-requests.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $request_ids = explode(',', $input['request_ids']);
        $product_requests = ProductRequest::with('company')->whereIn('request_id', $request_ids)->get();
        $company = $product_requests->first()->company;
        foreach ($product_requests as $product_request) {
            if ($product_request->company_id !== $company->id) {
                Flash::error('RFQs must contain products requests from only one customer.');

                return Redirect::back();
            }
        }
        $quotation_request = $this->execute(new AddNewQuotationRequestCommand($this->user, $company, $product_requests));

        return Redirect::route('quotation-requests.edit', $quotation_request->quotation_request_id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $quotation_request = QuotationRequest::with('company.associatedSuppliers', 'quotations')->where('quotation_request_id', $id)->first();
        if ($quotation_request) {
            $quotations = $quotation_request->quotations;
            $suppliers = $quotation_request->company->associatedSuppliers()->lists('name', 'id');
            asort($suppliers);

            return View::make('quotation-requests.edit', compact('quotation_request', 'quotations', 'suppliers'));
        }
        Flash::error('Your request could not be completed.');

        return Redirect::home();
    }

    public function duplicate($quotation_request_id)
    {
        $quotation_request = QuotationRequest::with('company.associatedSuppliers', 'quotations')->where('quotation_request_id', $quotation_request_id)->first();

        $product_requests = [];
        foreach($quotation_request->quotations as $quotation) {
            $product_requests[] = $quotation->productRequest;
        }
        $quotation_request = $this->execute(new AddNewQuotationRequestCommand($this->user, $quotation_request->company, $product_requests));

        return Redirect::route('quotation-requests.edit', $quotation_request->quotation_request_id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $quotation_request = QuotationRequest::with('company.associatedSuppliers', 'quotations')->where('quotation_request_id', $id)->first();
        $input = Input::all();
        $this->quotationRequestForm->validate($input);

        $this->execute(new UpdateQuotationRequestCommand($this->user, $quotation_request, $input['supplier_id'],
            Input::get('send_to_supplier', false), Input::get('message', ''), Input::get('cc_sender', false), key($input['transition'])));

        Flash::success('Quotation Request has been successfully updated.');

        return Redirect::route('quotation-requests.index');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $quotation_request = QuotationRequest::with('company.associatedSuppliers', 'quotations')->where('quotation_request_id', $id)->first();
        $quotation_request->apply('close');
        foreach($quotation_request->quotations as $quotation) {
            $quotation->apply('close');
            $quotation_request->quotations()->delete($quotation);
        }
        $quotation_request->delete();

        Flash::success('Quotation Request was deleted successfully.');

        return Redirect::route('quotation-requests.index');
    }


}
