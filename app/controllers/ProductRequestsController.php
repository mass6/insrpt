<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Insight\Core\CommandBus;
use Insight\Portal\Contracts\Contract;
use Insight\ProductDefinitions\ProductAttachment;
use Insight\ProductDefinitions\ProductImage;
use Insight\ProductRequests\Commands\AddNewProductRequestCommand;
use Insight\ProductRequests\Commands\ApplyProductRequestBulkTransitionsCommand;
use Insight\ProductRequests\Commands\UpdateProductRequestCommand;
use Insight\ProductRequests\Forms\DraftProductRequestForm;
use Insight\ProductRequests\Forms\SubmitProductRequestForm;
use Insight\ProductRequests\ProductRequest;
use Insight\ProductRequests\ProductRequestRepository;
use Insight\Quotations\QuotationRequest;
use Insight\Users\User;
use Laracasts\Flash\Flash;
use Laracasts\Utilities\JavaScript\Facades\JavaScript;

/**
 * Class ProductRequestsController
 */
class ProductRequestsController extends \BaseController
{

    use CommandBus;

    /**
     * @var ProductRequest
     */
    protected $productRequest;
    /**
     * @var DraftProductRequestForm
     */
    private $draftProductRequestForm;
    /**
     * @var SubmitProductRequestForm
     */
    private $submitProductRequestForm;


    /**
     * @param ProductRequestRepository $productRequest
     * @param DraftProductRequestForm $draftProductRequestForm
     * @param SubmitProductRequestForm $submitProductRequestForm
     */
    public function __construct(ProductRequestRepository $productRequest,
                                DraftProductRequestForm $draftProductRequestForm,
                                SubmitProductRequestForm $submitProductRequestForm)
    {

        parent::__construct();

        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('product-requests.*')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        });
        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('product-requests.create')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        }, ['only' => 'create']);
        $this->beforeFilter(function () {
            if (!$this->user->hasAccess('product-requests.edit')) {
                Flash::error('You do not have the appropriate privileges to view the requested page.');

                return Redirect::home();
            }
        }, ['only' => ['edit', 'update']]);

        $this->productRequest = $productRequest;
        $this->draftProductRequestForm = $draftProductRequestForm;
        $this->submitProductRequestForm = $submitProductRequestForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $filterBy = Input::get('filter', null);
        $filterValue = Input::get('value', null);

        return View::make('product-requests.index', compact('filterBy', 'filterValue'));
    }

    public function getProductRequests()
    {
        $filterBy = Input::get('filter', null);
        $filterValue = Input::get('value', null);

        if ($filterBy && $filterValue) {
            list( $requestLists, $productRequests ) = $this->getFilteredProductRequests($filterBy, $filterValue);
        } else {
            $product_requests = $this->productRequest->getAll();
            list( $requestLists, $productRequests ) = $this->generateJsonPayload($product_requests);
        }
        $customersList = $this->getCustomersList($productRequests);

        return Response::json(['data' => $productRequests, 'requestLists' => $requestLists, 'customersList' => $customersList]);
    }


    private function generateJsonPayload($product_requests)
    {
        $requestLists    = [ ];
        $productRequests = [ ];

        foreach ($product_requests as $product_request) {
            $productRequests[] = [
                'request_id'                   => $product_request->request_id,
                'product_description'          => $product_request->product_description,
                'category'                     => $product_request->category,
                'uom'                          => $product_request->uom,
                'customer'                     => $product_request->customer,
                'requester'                    => fullName($product_request->requested_by_first_name, $product_request->requested_by_last_name),
                'created_at'                   =>  Carbon::createFromFormat('Y-m-d H:i:s', $product_request->created_at)->format('Y-m-d'),
                'request_list_id'              => $product_request->list_name,
                'first_time_order_quantity'    => $product_request->first_time_order_quantity,
                'purchase_recurrence_quantity' => $product_request->volume_requested,
                'purchase_recurrence'          => ProductRequest::$purchaseRecurrence[$product_request->purchase_recurrence],
                'sku'                          => $product_request->sku,
                'current_price'                => $product_request->current_price,
                'current_price_currency'       => $product_request->current_price_currency,
                'current_supplier'             => $product_request->current_supplier,
                'current_supplier_contact'     => $product_request->current_supplier_contact,
                'reference1'                   => $product_request->reference1,
                'reference2'                   => $product_request->reference2,
                'reference3'                   => $product_request->reference3,
                'reference4'                   => $product_request->reference4,
                'cataloguing_item_code'        => $product_request->cataloguing_item_code,
                'remarks'                      => $product_request->remarks,
                'status'                       => ProductRequest::getStateLabel($product_request->state),
                'completed_at'                 => $product_request->completed_at ? Carbon::createFromFormat('Y-m-d H:i:s', $product_request->completed_at)->format('Y-m-d') : null,
                'quotations'                   => $product_request->quotations_count ? $product_request->quotations_count : '',
            ];
            if ($product_request->list_name) {
                $requestLists[] = trim($product_request->list_name);
            }
        }

        return [ array_values(array_unique($requestLists)),  $productRequests ];
    }

    private function getFilteredProductRequests($filterBy, $filterValue)
    {
        if ($filterBy == 'quotation-request') {
            $quotationRequest = QuotationRequest::with('quotations.productRequest.requestedBy.company',
                'quotations.productRequest.quotationsReceived')->where('quotation_request_id',
                $filterValue)->acl()->first();
            $product_requests = [ ];
            foreach ($quotationRequest->quotations as $quotation) {
                if ($quotation->productRequest) {
                    $product_requests[] = $quotation->productRequest;
                }
            }
        } else {
            $product_requests = $this->productRequest->filterBy($filterBy, $filterValue);
        }
        $product_requests = $this->addCustomProperties($product_requests);
        list( $requestLists, $productRequests ) = $this->generateJsonPayload($product_requests);

        return [ $requestLists, $productRequests ];
    }

    protected function addCustomProperties($product_requests)
    {
        $productRequests = [];
        foreach ($product_requests as $product_request) {
            $product_request->requested_by_first_name = $product_request->requestedBy->first_name;
            $product_request->requested_by_last_name = $product_request->requestedBy->last_name;
            $product_request->created_by_first_name = $product_request->createdBy->first_name;
            $product_request->created_by_last_name = $product_request->createdBy->last_name;
            $product_request->customer = $product_request->company->name;
            $product_request->list_name = $product_request->productRequestList ? $product_request->productRequestList->name : null;
            $product_request->quotations_count = $product_request->quotationsReceived->count();
            $productRequests[] = $product_request;
        }

        return $productRequests;
    }

    public function findByQuotationRequest($id)
    {
        $filterBy = 'quotation-request';
        $filterValue = $id;

        return View::make('product-requests.index', compact('filterBy', 'filterValue'));
    }

    private function getCustomersList($productRequests)
    {
        $customersList = [];
        foreach ($productRequests as $productRequest) {
            if (! in_array($productRequest['customer'], $customersList)) {
                $customersList[] = $productRequest['customer'];
            }
        }

        return $customersList;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        $categories = $this->user->company->settings()->get('product-requests.procurement-categories', ['Default']);
        $categoriesList = [null => 'Select'] + array_combine(array_map('strtolower', $categories), $categories);
        $company_settings = $this->user->company->settings()->all();
        $contracts = $unassigned_contracts = Contract::getContractIdsByDataGroup($this->user->company->settings()->get('portal.dataGroup'));
        $transitions = ProductRequest::getInitialTransitions();

        return View::make('product-requests.create', compact('categoriesList', 'company_settings', 'contracts', 'unassigned_contracts', 'transitions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        $input = Input::all();
        $input['reference1'] = Input::get('reference1', null);
        $input['reference2'] = Input::get('reference2', null);
        $input['reference3'] = Input::get('reference3', null);
        $input['reference4'] = Input::get('reference4', null);
        $input['transition'] = key($input['transition']);

        $requested_by_id = Input::get('requested_by_id', $this->user->id);
        $requester = User::findOrFail($requested_by_id);

        $formdata = $this->setReferenceFields($input);
        if ($input['transition'] == 'save_draft') {
            $this->draftProductRequestForm->validate($formdata);
        } else {
            $this->submitProductRequestForm->validate($formdata);
        }

        $attachments = object_to_array(json_decode($input['file-uploads'])) ?: [];
        $this->execute(new AddNewProductRequestCommand(
            $this->user, $requester, $input['product_description'], $input['uom'], $input['category'], $input['purchase_recurrence'],
            $input['first_time_order_quantity'], $input['volume_requested'], $input['sku'], $input['current_price'], $input['current_price_currency'],
            $input['current_supplier'], $input['current_supplier_contact'], Input::get('contracts_assigned', []), $input['reference1'], $input['reference2'],
            $input['reference3'], $input['reference4'], $input['remarks'], $attachments, $input['transition']
        ));

        Flash::success('Product request draft has been saved successfully.');

        return Redirect::route('product-requests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function show($id)
    {
        $product_request = $this->productRequest->findByRequestId($id);
        if (!$product_request) {
            Flash::error('Product request not found.');
            return Redirect::home();
        }

        $proposals   = $this->getProposalsUserCanView($product_request);
        $images = $product_request->images;
        $attachments = $product_request->attachments;
        $transitions = $product_request->getAvailableTransitions();

        return View::make('product-requests.show', compact('product_request', 'proposals', 'images', 'attachments', 'transitions'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $product_request = $this->productRequest->findByRequestId($id);
        if (!$product_request || !canEdit($product_request, $this->user)) {
            Flash::error("You can not have permissions to edit products requests that are {$product_request->currentStateLabel()}.");

            return Redirect::route('product-requests.index');
        }

        $proposals   = $this->getProposalsUserCanView($product_request);
        $transitions = $product_request->getAvailableTransitions();
        $categories = $product_request->company->settings()->get('product-requests.procurement-categories', ['Default']);
        $categoriesList = [null => 'Select'] + array_combine(array_map('strtolower', $categories), $categories);
        $company_settings = $product_request->company->settings()->all();
        $contracts = Contract::getContractIdsByDataGroup($this->user->company->settings()->get('portal.dataGroup'));
        $assigned_contracts = $product_request->contracts->lists('name', 'id');
        $unassigned_contracts = array_diff($contracts, $assigned_contracts);
        JavaScript::put([
            'algolia' => [
                'id' => getenv('ALGOLIA_APP_ID'),
                'key' => getenv('ALGOLIA_SEARCH_ONLY_KEY'),
                'productRequestsIndex' => getenv('PRODUCT_REQUESTS_INDEX'),
                'productsIndex' => getenv('PRODUCTS_INDEX'),
                'customer' => array_get($company_settings, 'portal.dataGroup'),
                'customerName' => $product_request->company->name,
                'siteOwner' => siteOwner()->settings()->get('portal.dataGroup'),
                'product_request_id' => $product_request->id,
            ]
        ]);

        return View::make('product-requests.edit', compact('product_request', 'proposals', 'categoriesList', 'company_settings', 'contracts', 'unassigned_contracts', 'transitions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        $product_request = $this->productRequest->findByRequestId($id);
        $input = Input::all();
        $input['reference1'] = Input::get('reference1', null);
        $input['reference2'] = Input::get('reference2', null);
        $input['reference3'] = Input::get('reference3', null);
        $input['reference4'] = Input::get('reference4', null);
        $input['transition'] = key($input['transition']);
        $input['reason_closed'] = Input::get('reason_closed', null);

        $formdata = $this->setReferenceFields($input);
        if ($input['transition'] == 'save_draft') {
            $this->draftProductRequestForm->validate($formdata);
        } else {
            $this->submitProductRequestForm->validate($formdata);
        }

        $attachments = object_to_array(json_decode($input['file-uploads'])) ?: [];
        $this->execute(new UpdateProductRequestCommand(
            $product_request, $this->user, $input['product_description'], $input['uom'], $input['category'], $input['purchase_recurrence'],
            $input['first_time_order_quantity'], $input['volume_requested'], $input['sku'], $input['current_price'], $input['current_price_currency'],
            $input['current_supplier'], $input['current_supplier_contact'], Input::get('contracts_assigned', []), $input['reference1'], $input['reference2'],
            $input['reference3'], $input['reference4'], $input['cataloguing_item_code'], $input['cataloguing_product_name'], $input['remarks'], $attachments, $input['transition'], Input::get('reason_closed', null)
        ));

        if ($input['transition'] === 'create_proposal') {
            return Redirect::action('ProductProposalsController@create', [$product_request->request_id]);
        }

        Flash::success('Product request has been successfully updated.');

        // Redirect user back to form when saving
        if (strtolower(substr($input['transition'], 0, 4)) === 'save' || strtolower($input['transition']) === 'reopen') {
            return Redirect::back();
        }

        return Redirect::route('product-requests.index');
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
     * @return mixed
     */
    public function applyTransition()
    {
        $transition = Input::get('transition', null);
        $requestIds = Input::get('request_ids', null);
        if ($requestIds) {
            $requestIds = explode(',', Input::get('request_ids', null));
        }

        if ($transition && $requestIds) {
            foreach ($requestIds as $id) {
                $productRequest = ProductRequest::where('request_id', $id)->first();
                if (!$productRequest->can($transition)) {
                    Flash::error('Can not apply the selected action to one or more of the selected product requests.');

                    return Redirect::back();
                }
            }
            if ($transition === 'close' && !Input::get('reason_closed', null)) {
                Flash::error('You must select a reason when closing requests.');

                return Redirect::back();
            }
            $this->execute(new ApplyProductRequestBulkTransitionsCommand($requestIds, $transition, $this->user));
            Flash::success('Action successfully applied to the selected product requests.');
        }

        return Redirect::back();

    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeAttachments()
    {
        $file = Input::file('file');

        $mimeTypes = ['image/png', 'image/jpeg', 'image/bmp', 'image/x-windows-bmp', 'image/gif'];
        if (in_array($file->getMimeType(), $mimeTypes)) {

            return $this->storeImage($file);
        } else {
            $attachment = ProductAttachment::create([
                'attachable_type' => ProductRequest::class,
                'attachment'      => $file,
            ]);

            return Response::json(array('id' => $attachment->id, 'type' => 'attachment'));
        }

    }

    /**
     * @param $type
     * @param $id
     * @return \Illuminate\Http\JsonResponse
     * @internal param $filename
     */
    public function deleteAttachments($type, $id)
    {
        if ($type === 'image') {
            $image = ProductImage::findOrFail($id);
            $image->image = STAPLER_NULL;
            $image->save();
            $image->delete();
        } elseif ($type === 'attachment') {
            $attachment = ProductAttachment::findOrFail($id);
            $attachment->attachment = STAPLER_NULL;
            $attachment->save();
            $attachment->delete();
        } else {
            Log::error('Cannot delete attachment');
        }

        return Response::json(array('result' => 'deleted'));
    }

    /**
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    protected function storeImage($file)
    {
        $image = ProductImage::create([
            'imageable_type' => ProductRequest::class,
            'image'          => $file,
        ]);

        return Response::json(array('id' => $image->id, 'type' => 'image'));
    }

    /**
     * @param $data
     * @return mixed
     */
    private function setReferenceFields($data)
    {
        $settings = $this->user->company->settings();
        for ($r = 1; $r <= 4; $r ++) {
            $data["reference{$r}_label"] = $settings->get("product-requests.reference{$r}.label", "Reference field {$r}");
            $data["reference{$r}_enabled"] = $settings->get("product-requests.reference{$r}.enabled", false);
            $data["reference{$r}_required"] = $settings->get("product-requests.reference{$r}.required", false);
        }

        return $data;
    }


    /**
     * @param $product_request
     *
     * @return array
     */
    private function getProposalsUserCanView($product_request)
    {
        $proposals = [ ];
        foreach ($product_request->proposals as $proposal) {
            if ( ! ( $proposal->getState() == 'DRA' && ! $this->user->hasAccess('product-proposals.save_draft') )) {
                $proposals[] = $proposal;
            }
        }

        return $proposals;
    }

}
