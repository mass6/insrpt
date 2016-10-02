<?php

use Insight\Companies\Company;
use Insight\Companies\Supplier;
use Insight\ProductRequests\ProductRequest;
use Insight\Quotations\Forms\QuotationForm;
use Insight\Quotations\Quotation;
use Insight\Quotations\QuotationRequest;

/**
 * Class QuotationsController
 */
class QuotationsController extends \BaseController
{

    /**
     * @var QuotationForm
     */
    private $quotationForm;

    /**
     * @param QuotationForm $quotationForm
     */
    public function __construct(QuotationForm $quotationForm)
    {
        parent::__construct();
        $this->quotationForm = $quotationForm;

        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('quotation*')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::to(URL::previous());
            }
        });
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        if (Request::ajax()) {
            return $this->getQuotationsJson();
        }

        $search = Input::get('search', null);

        $quotations = Quotation::with('productRequest', 'company', 'supplier', 'quotationRequest')->where('state', '<>', 'DRA')->get();

        if ($isSiteOwner = isSiteOwner($this->user)) {
            $productRequests = ProductRequest::with('company')
                ->join('companies', 'product_requests.company_id', '=', 'companies.id')
                ->orderBy('companies.name', 'ASC')
                ->orderBy('product_requests.request_id', 'ASC')
                ->get();
            $companies = [null => 'select...'] + Company::where('type', 'customer')->lists('name', 'id');
            $suppliers = Supplier::lists('name', 'id');
        } else {
            $productRequests = ProductRequest::where('company_id', $this->user->company->id)->get();
            $companies = [$this->user->company->id => $this->user->company->name];
            $suppliers = $this->user->company->associatedSuppliers->lists('name', 'id');
        }

        $companyOptions = $this->createJsEditorSelectOptionsFromList([null => 'select...'] + $companies);
        $supplierOptions = $this->createJsEditorSelectOptionsFromList([null => 'select...'] + $suppliers);

        $productRequestsList = [];
        foreach ($productRequests as $productRequest) {
            $productRequestsList[$productRequest->request_id] =
                '[' . $productRequest->company->name . '] : '
                . $productRequest->request_id
                . ' - '
                . substr($productRequest->product_description, 0, 16);
        }
        $productRequestOptions = $this->createJsEditorSelectOptionsFromList([null => 'select...'] + $productRequestsList);

        return View::make('quotations.index', compact('quotations', 'companyOptions', 'supplierOptions', 'productRequestOptions', 'search'));
    }

    /**
     * @return mixed
     */
    public function getQuotationsJson()
    {
        $quotations = Quotation::with('company', 'quotationRequest', 'productRequest', 'supplier')->acl()->get()->toArray();
        $priceFields = Quotation::$currencyFields;
        foreach ($quotations as $key => $quotation) {
            foreach ($priceFields as $price) {
                $quotation[$price] ? (string) number_format($quotation[$price] / 100, 2, '.', '') : null;
            }
            $quotations[$key] = $quotation;
        }

        return Response::json(['data' => $quotations]);
    }

    /**
     * @param $id
     * @return mixed
     */
    public function getByQuotationRequest($id)
    {
        $quotation_request = QuotationRequest::with('quotations.productRequest')->findOrFail($id);
        $quotations = $quotation_request->quotations->toArray();
        foreach ($quotations as $key => $quotation) {
            foreach (Quotation::$currencyFields as $field) {
                $quotation[$field] = (string) number_format($quotation[$field] / 100, 2, '.', '');
            }
            $quotations[$key] = $quotation;
        }

        return Response::json(['data' => $quotations]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return View::make('quotations.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $data = Input::get('data');
        $payload = $data[0];
        $payload['actionCreate'] = true;
        $validation = $this->quotationForm->validate($payload);
        if ($validation['result'] === 'failed') {
            return Response::json(['fieldErrors' => $validation['errors']]);
        }

        // validation passes, update the supplier
        $payload['created_by_id'] = $this->user->id;
        $quotation = Quotation::create($payload);
        $response[] = [
            'id'                            => $quotation->id,
            'quotation_id'                  => $quotation->quotation_id,
            'company'                       => $quotation->company,
            'quotation_request'             => $quotation->quotationRequest,
            'product_request'               => $quotation->productRequest,
            'product_description'           => $quotation->product_description,
            'uom'                           => $quotation->uom,
            'volume'                        => $quotation->volume,
            'current_price'                 => $quotation->current_price,
            'current_price_currency'        => $quotation->current_price_currency,
            'supplier'                      => $quotation->supplier,
            'quotation_date'                => $quotation->quotation_date,
            'supplier_reference'            => $quotation->supplier_reference,
            'suppliers_product_name'        => $quotation->suppliers_product_name,
            'suppliers_product_description' => $quotation->suppliers_product_description,
            'suppliers_product_sku'         => $quotation->suppliers_product_sku,
            'suppliers_uom'                 => $quotation->suppliers_uom,
            'suppliers_quantity'            => $quotation->suppliers_quantity,
            'unit_price'                    => $quotation->unit_price,
            'price_currency'                => $quotation->price_currency,
            'total_price'                   => $quotation->total_price,
            'valid_until'                   => $quotation->valid_until,
            'delivery_terms'                => $quotation->delivery_terms,
            'payment_terms'                 => $quotation->payment_terms,
            'state'                         => $quotation->state,
        ];

        return Response::json(['data' => $response]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {

    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $data = Input::get('data');
        $response = [];

        foreach ($data as $id => $payload) {

            $validation = $this->quotationForm->validate($payload);
            if ($validation['result'] === 'failed') {
                return Response::json(['fieldErrors' => $validation['errors']]);
            }

            // validation passes, update the supplier
            $quotation = Quotation::with('productRequest')->find($id);
            $payload['updated_by_id'] = $this->user->id;
            $quotation->update($payload);

            if (array_get($payload, 'received') == true) {
                if ($quotation->can('receive_quote')) {
                    $quotation->apply('receive_quote');
                }
            }

            $response[] = [
                'id'                            => $quotation->id,
                'quotation_id'                  => $quotation->quotation_id,
                'company'                       => $quotation->company,
                'quotation_request'             => $quotation->quotationRequest,
                'product_request'               => $quotation->productRequest,
                'product_description'           => $quotation->product_description,
                'uom'                           => $quotation->uom,
                'volume'                        => $quotation->volume,
                'current_price'                 => $quotation->current_price,
                'current_price_currency'        => $quotation->current_price_currency,
                'supplier'                      => $quotation->supplier,
                'quotation_date'                => $quotation->quotation_date,
                'supplier_reference'            => $quotation->supplier_reference,
                'suppliers_product_name'        => $quotation->suppliers_product_name,
                'suppliers_product_description' => $quotation->suppliers_product_description,
                'suppliers_product_sku'         => $quotation->suppliers_product_sku,
                'suppliers_uom'                 => $quotation->suppliers_uom,
                'suppliers_quantity'            => $quotation->suppliers_quantity,
                'unit_price'                    => $quotation->unit_price,
                'price_currency'                => $quotation->price_currency,
                'total_price'                   => $quotation->total_price,
                'valid_until'                   => $quotation->valid_until,
                'delivery_terms'                => $quotation->delivery_terms,
                'payment_terms'                 => $quotation->payment_terms,
                'state'                         => $quotation->state,
            ];

        }

        return Response::json(['data' => $response]);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        $data = Input::get('data');
        $response = [];
        foreach ($data as $id => $payload) {
            $quotation = Quotation::find($id);
            $quotation->delete();
            $response[] = [
                'id'                            => $quotation->id,
                'quotation_id'                  => $quotation->quotation_id,
                'company'                       => $quotation->company,
                'quotation_request'             => $quotation->quotationRequest,
                'product_request'               => $quotation->productRequest,
                'product_description'           => $quotation->product_description,
                'uom'                           => $quotation->uom,
                'volume'                        => $quotation->volume,
                'current_price'                 => $quotation->current_price,
                'current_price_currency'        => $quotation->current_price_currency,
                'supplier'                      => $quotation->supplier,
                'quotation_date'                => $quotation->quotation_date,
                'supplier_reference'            => $quotation->supplier_reference,
                'suppliers_product_name'        => $quotation->suppliers_product_name,
                'suppliers_product_description' => $quotation->suppliers_product_description,
                'suppliers_product_sku'         => $quotation->suppliers_product_sku,
                'suppliers_uom'                 => $quotation->suppliers_uom,
                'suppliers_quantity'            => $quotation->suppliers_quantity,
                'unit_price'                    => $quotation->unit_price,
                'price_currency'                => $quotation->price_currency,
                'total_price'                   => $quotation->total_price,
                'valid_until'                   => $quotation->valid_until,
                'delivery_terms'                => $quotation->delivery_terms,
                'payment_terms'                 => $quotation->payment_terms,
                'state'                         => $quotation->state,
            ];
        }

        return Response::json(['data' => $response]);
    }


    /**
     * @param $data
     * @return string
     */
    private function createJsEditorSelectOptionsFromList($data)
    {
        $options = [];
        foreach ($data as $value => $label) {
            $options[] = ['label' => utf8_encode($label), 'value' => (string) $value];
        }

        return json_encode($options, JSON_HEX_APOS);
    }


}
