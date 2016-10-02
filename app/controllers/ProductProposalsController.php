<?php

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Insight\Core\CommandBus;
use Insight\ProductDefinitions\ProductAttachment;
use Insight\ProductDefinitions\ProductImage;
use Insight\ProductProposals\Commands\ApplyProductProposalBulkTransitionsCommand;
use Insight\ProductProposals\Commands\AttachNewProductProposalCommand;
use Insight\ProductProposals\Commands\ProductProposalApprovalCommand;
use Insight\ProductProposals\Commands\UpdateProductProposalCommand;
use Insight\ProductProposals\Forms\AjaxProductProposalForm;
use Insight\ProductProposals\Forms\ProductProposalForm;
use Insight\ProductProposals\ProductProposal;
use Insight\ProductRequests\ProductRequest;
use Insight\ProductRequests\ProductRequestRepository;
use Insight\Quotations\Quotation;

/**
 * Class ProductProposalsController
 */
class ProductProposalsController extends \BaseController
{

    use CommandBus;

    /**
     * @var ProductRequestRepository
     */
    private $productRequestRepository;
    /**
     * @var ProductProposalForm
     */
    private $productProposalForm;
    /**
     * @var AjaxProductProposalForm
     */
    private $ajaxProductProposalForm;


    /**
     * @param ProductRequestRepository $productRequestRepository
     * @param ProductProposalForm $productProposalForm
     */
    public function __construct(ProductRequestRepository $productRequestRepository,
                                ProductProposalForm $productProposalForm, AjaxProductProposalForm $ajaxProductProposalForm)
    {
        $this->productRequestRepository = $productRequestRepository;
        parent::__construct();

        $this->productProposalForm = $productProposalForm;
        $this->ajaxProductProposalForm = $ajaxProductProposalForm;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        $proposals = ProductProposal::with('productRequest.quotations', 'assignedTo', 'company')->acl()->get();

        return View::make('product-proposals.index', compact('proposals'));
    }

    /**
     * @return mixed
     */
    public function getUserApprovals()
    {
        $proposals = ProductProposal::with('productRequest.quotations', 'assignedTo', 'company')->acl()
            ->where('assigned_to_id', $this->user->id)
            ->where('state', 'APP')
            ->get();

        $filter = 'Pending My Approval';

        return View::make('product-proposals.index', compact('proposals', 'filter'));
    }

    /**
     * Get data for Proposals Workbench
     */
    public function workbench()
    {

        if (Request::ajax()) {
            return $this->getWorkbench();
        }

        return View::make('product-proposals.workbench');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     */
    private function getWorkbench()
    {
        $proposals = ProductProposal::with('productRequest.productRequestList', 'company', 'quotation.supplier')->get();

        $proposals = $this->formatJsonFields($proposals);

        return Response::json(['data' => $proposals]);
    }

    /**
     * @param $proposals
     * @return array
     */
    private function formatJsonFields($proposals)
    {

        $proposalsArray = [];

        foreach ($proposals as $key => $proposal) {

            $proposalsArray[$key] = $proposal->toArray();

            $proposalsArray[$key]['status'] = $proposal->currentStateLabel();
            $proposal->status = $proposal->currentStateLabel();

            foreach (ProductProposal::$currencyFields as $price) {
                $proposalsArray[$key]['price'] = $proposal->{$price} ?: null;
            }
            if ($proposal->productRequest) {
                foreach (ProductRequest::$currencyFields as $price) {
                    $proposalsArray[$key]['product_request'][$price] = $proposal->productRequest->{$price} ?: null;
                }
            }
            if ($proposal->quotation) {
                foreach (Quotation::$currencyFields as $price) {
                    $proposalsArray[$key]['quotation'][$price] = $proposal->quotation->{$price} ?: null;
                }
            }
        }

        return $proposalsArray;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @param $id
     * @param null $quotationId
     * @return Response
     */
    public function create($id, $quotationId = null)
    {

        $product_request = $this->productRequestRepository->findByRequestId($id);
        if (!$product_request->canAttachProposal()) {
            Flash::error('You cannot create a new proposal while there is currently another active proposal for the product request.');
            return Redirect::home();
        }

        $quotation = Quotation::where('quotation_id', $quotationId)->first() ?: null;
        $transitions = ProductProposal::getInitialTransitions();

        return View::make('product-proposals.create', compact('product_request', 'quotation', 'transitions'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param $id
     * @return Response
     * @throws \Laracasts\Validation\FormValidationException
     */
    public function store($id)
    {

        $productRequest = $this->productRequestRepository->findById($id);
        $input = Input::all();
        $input['transition'] = key($input['transition']);
        $this->productProposalForm->validate($input);

        $attachments = object_to_array(json_decode($input['file-uploads'])) ?: [];

        $this->execute(new AttachNewProductProposalCommand(
            $this->user, $productRequest, Input::get('quotation_id', null), $productRequest->company_id, $input['product_name'], $input['product_description'],
            $input['uom'], $input['volume'], $input['sku'], $input['price'], $input['price_currency'],
            Input::get('display_quotation_details', false), $input['remarks'], $input['transition'], $attachments
        ));

        Flash::success('Product Proposal has been created successfully.');

        return Redirect::route('product-requests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param $request_id
     * @param $proposal_id
     * @return Response
     */
    public function show($request_id, $proposal_id)
    {
        $product_proposal = ProductProposal::where('proposal_id', $proposal_id)->first();
        $product_request = $product_proposal->productRequest;
        $transitions = $product_proposal->getAvailableTransitions();

        return View::make('product-proposals.show', compact('product_proposal', 'product_request', 'transitions'));

    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param $request_id
     * @param $proposal_id
     * @return Response
     */
    public function edit($request_id, $proposal_id)
    {
        $product_proposal = ProductProposal::with('productRequest', 'quotation')->where('proposal_id', $proposal_id)->first();
        if (!$product_proposal->isEditable()) {
            Flash::error('Proposal is not currently editable.');
            return Redirect::back();
        }

        $product_request = $product_proposal->productRequest;
        $quotation = $product_proposal->quotation;
        $transitions = $product_proposal->getAvailableTransitions();

        return View::make('product-proposals.edit', compact('product_proposal', 'product_request', 'quotation', 'transitions'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update($id)
    {
        if (Request::ajax()) {
            return $this->ajaxUpdate();
        }

        $product_proposal = ProductProposal::with('productRequest')->findOrFail($id);
        $productRequest = $product_proposal->productRequest;
        $input = Input::all();
        $input['transition'] = key($input['transition']);
        $this->productProposalForm->validate($input);

        $attachments = object_to_array(json_decode($input['file-uploads'])) ?: [];

        $this->execute(new UpdateProductProposalCommand(
            $product_proposal, $this->user, $productRequest, $productRequest->company_id, $input['product_name'], $input['product_description'],
            $input['uom'], $input['volume'], $input['sku'], $input['price'], $input['price_currency'],
            Input::get('display_quotation_details', false), $input['remarks'], $input['transition'], $attachments
        ));

        Flash::success('Product Proposal has been updated successfully.');

        return Redirect::route('product-requests.index');
    }

    /**
     * @return \Illuminate\Http\JsonResponse
     * @throws \Laracasts\Validation\FormValidationException
     */
    public function ajaxUpdate()
    {
        $data = Input::get('data');
        $response = [];

        foreach ($data as $id => $payload) {

            $validation = $this->ajaxProductProposalForm->validate($payload);
            if ($validation['result'] === 'failed') {
                return Response::json(['fieldErrors' => $validation['errors']]);
            }

            // validation passes, update the supplier
            $proposal = ProductProposal::with('productRequest.productRequestList', 'company', 'quotation.supplier')->find($id);
            $proposal->update($payload);

            $proposal = $this->formatJsonFields([$proposal])[0];

            $response[] = [
                'id'                  => $proposal['id'],
                'status'              => $proposal['status'],
                'proposal_id'         => $proposal['proposal_id'],
                'product_request'     => $proposal['product_request'] ?: null,
                'quotation'           => $proposal['quotation'] ?: null,
                'company'             => $proposal['company'] ?: null,
                'product_name'        => $proposal['product_name'],
                'product_description' => $proposal['product_description'],
                'sku'                 => $proposal['sku'],
                'price'               => $proposal['price'],
                'price_currency'      => $proposal['price_currency'],
            ];
        }

        return Response::json(['data' => $response]);
    }


    /**
     * @return mixed
     */
    public function applyTransition()
    {
        $transition = Input::get('transition', null);
        $proposalIds = explode(',', Input::get('proposal_ids', null));

        if ($transition && $proposalIds) {
            foreach ($proposalIds as $id) {
                $proposal = ProductProposal::where('proposal_id', $id)->first();
                if (!$proposal->can($transition)) {
                    Flash::error("Can not {$transition} one or more of the selected proposals.");

                    return Redirect::back();
                }
            }

            $this->execute(new ApplyProductProposalBulkTransitionsCommand($proposalIds, $transition, $this->user));
        }

        Flash::success('Action successfully applied to the selected product requests.');

        return Redirect::back();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function approval($id)
    {
        $product_proposal = ProductProposal::with('productRequest')->findOrFail($id);
        $transition = key(Input::get('transition'));
        $remarks = Input::get('remarks', null);
        $reject_reason = Input::get('reject_reason', null);

        $this->execute(new ProductProposalApprovalCommand($this->user, $product_proposal, $transition, $remarks, $reject_reason));

        return Redirect::route('product-requests.show', $product_proposal->productRequest->request_id);
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
                'attachable_type' => ProductProposal::class,
                'attachment'      => $file,
            ]);

            return Response::json(array('id' => $attachment->id, 'type' => 'attachment'));
        }

    }

    /**
     * @param $file
     * @return \Illuminate\Http\JsonResponse
     */
    protected function storeImage($file)
    {
        $image = ProductImage::create([
            'imageable_type' => ProductProposal::class,
            'image'          => $file,
        ]);

        return Response::json(array('id' => $image->id, 'type' => 'image'));
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
            Log::info('cannot delete');
        }

        return Response::json(array('result' => 'deleted'));
    }


}
