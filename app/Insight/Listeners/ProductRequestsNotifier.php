<?php

namespace Insight\Listeners;

use Insight\Mailers\ProductRequestsMailer;
use Insight\Mailers\QuotationRequestsMailer;
use Insight\ProductRequests\Events\ProductRequestEvent;
use Insight\ProductRequests\Events\ProductRequestListWasUploaded;
use Insight\ProductRequests\Events\ProductRequestWasClosed;
use Insight\ProductRequests\Events\ProductRequestWasCompleted;
use Insight\ProductRequests\Events\ProductRequestWasCreated;
use Insight\ProductRequests\ProductRequest;
use Insight\Quotations\Events\QuotationRequestWasSent;
use Insight\Quotations\QuotationRequestFileGenerator;

/**
 * Class ProductRequestsNotifier
 * @package Insight\Listeners
 */
class ProductRequestsNotifier extends EventListener
{

    /**
     * @var QuotationRequestsMailer
     */
    private $quotationRequestsMailer;
    /**
     * @var ProductRequestsMailer
     */
    private $productRequestsMailer;

    /**
     * @param QuotationRequestsMailer $quotationRequestsMailer
     * @param ProductRequestsMailer $productRequestsMailer
     */
    public function __construct(QuotationRequestsMailer $quotationRequestsMailer, ProductRequestsMailer $productRequestsMailer)
    {
        $this->quotationRequestsMailer = $quotationRequestsMailer;
        $this->productRequestsMailer = $productRequestsMailer;
    }

    /**
     * @param ProductRequestWasCreated $event
     */
    public function whenProductRequestWasCreated(ProductRequestWasCreated $event)
    {
        $productRequest = $event->productRequest;

        if (!$productRequest->list_id) {
            $creatorEmail = $productRequest->createdBy->email;
            $requesterEmail = $productRequest->requestedBy->email;

            $data = $this->generateProductRequestData($productRequest);

            $this->productRequestsMailer->sendProductRequestWasCreated($creatorEmail, $requesterEmail, $data);
        }
    }

    /**
     * @param ProductRequestEvent $event
     */
    public function whenProductRequestWasReassignedToRequester(ProductRequestEvent $event)
    {
        $productRequest = $event->productRequest;
        $emailRecipient = $productRequest->requestedBy->email;

        $data = [
            'productRequest' => $productRequest->toArray(),
            'requestedBy'    => $productRequest->requestedBy->name(),
            'remarks'        => $productRequest->remarks,
        ];

        $this->productRequestsMailer->sendProductRequestWasReassignedToRequester($emailRecipient, $data);
    }

    /**
     * @param ProductRequestWasCompleted $event
     */
    public function whenProductRequestWasCompleted(ProductRequestWasCompleted $event)
    {
        $productRequest = $event->productRequest;
        $emailRecipient = $productRequest->requestedBy->email;

        $data = [
            'productRequest' => $productRequest->toArray(),
            'requestedBy'    => $productRequest->requestedBy->name(),
            'remarks'        => $productRequest->remarks,
        ];

        $this->productRequestsMailer->sendProductRequestWasCompleted($emailRecipient, $data);
    }

    /**
     * @param ProductRequestWasClosed $event
     */
    public function whenProductRequestWasClosed(ProductRequestWasClosed $event)
    {
        $productRequest = $event->productRequest;
        $emailRecipient = $productRequest->requestedBy->email;

        $data = [
            'productRequest' => $productRequest->toArray(),
            'requestedBy'    => $productRequest->requestedBy->name(),
            'reason_closed'  => ProductRequest::$reasonsClosed[$productRequest->reason_closed],
            'remarks'        => $productRequest->remarks,
        ];

        $this->productRequestsMailer->sendProductRequestWasClosed($emailRecipient, $data);
    }

    /**
     * @param ProductRequestEvent $event
     */
    public function whenProductRequestWasReopened(ProductRequestEvent $event)
    {
        $productRequest = $event->productRequest;
        $emailRecipient = $productRequest->requestedBy->email;

        $data = [
            'productRequest' => $productRequest->toArray(),
            'requestedBy'    => $productRequest->requestedBy->name(),
            'remarks'        => $productRequest->remarks,
        ];

        $this->productRequestsMailer->sendProductRequestWasReopened($emailRecipient, $data);
    }

    /**
     * @param ProductRequestListWasUploaded $event
     */
    public function whenProductRequestListWasUploaded(ProductRequestListWasUploaded $event)
    {
        $productRequestList = $event->productRequestList;
        $creatorEmail = $productRequestList->createdBy->email;
        $requesterEmail = $productRequestList->requestedBy->email;
        $productRequests = [];
        foreach ($productRequestList->productRequests as $productRequest) {
            $productRequests[] = $productRequest->toArray();
        }

        $data = [
            'productRequestList' => $productRequestList->toArray(),
            'createdBy'          => $productRequestList->createdBy->name(),
            'requestedBy'        => $productRequestList->requestedBy->name(),
            'company'            => $productRequestList->requestedBy->company->toArray(),
            'productRequests'    => $productRequests,
        ];

        $this->productRequestsMailer->sendProductRequestListWasUploaded($creatorEmail, $requesterEmail, $data);
    }


    /**
     * @param QuotationRequestWasSent $event
     */
    public function whenQuotationRequestWasSent(QuotationRequestWasSent $event)
    {
        $quotationRequest = $event->quotationRequest;
        $fileGenerator = new QuotationRequestFileGenerator($quotationRequest);
        $spreadsheet = $fileGenerator->generate();
        $emailRecipient = $quotationRequest->supplier->email;
        $fromEmail = $quotationRequest->updatedBy->email;
        $ccSender = $quotationRequest->ccSender;

        $data = [
            'quotationRequest' => $quotationRequest->toArray(),
            'supplier'         => $quotationRequest->supplier->name,
            'sentTo'           => $quotationRequest->supplier->primary_contact ?: '',
            'sentBy'           => $quotationRequest->updatedBy->name(),
            'company'          => $quotationRequest->updatedBy->company->name,
            'num_products'     => count($quotationRequest->quotations),
            'message_body'     => $quotationRequest->message,
        ];

        $this->quotationRequestsMailer->sendQuotationRequestWasSent($emailRecipient, $fromEmail, $ccSender, $data, $spreadsheet);
        unset($quotationRequest->ccSender);
        $quotationRequest->sent = true;
        $quotationRequest->save();
    }


    /**
     * Set the standard data array of Product Request information
     *
     * @param ProductRequest $productRequest
     * @return array
     */
    private function generateProductRequestData(ProductRequest $productRequest)
    {
        return [
            'productRequest'           => $productRequest->toArray(),
            'createdBy'                => $productRequest->createdBy->name(),
            'requestedBy'              => $productRequest->requestedBy->name(),
            'company'                  => $productRequest->requestedBy->company->toArray(),
            'product_description'      => $productRequest->product_description,
            'uom'                      => $productRequest->uom,
            'category'                 => $productRequest->category,
            'purchase_recurrence'      => $productRequest->purchase_recurrence ? ProductRequest::$purchaseRecurrence[$productRequest->purchase_recurrence] : null,
            'first_time_order_quantity'         => $productRequest->first_time_order_quantity,
            'volume_requested'         => $productRequest->volume_requested,
            'sku'                      => $productRequest->sku,
            'current_price'            => $productRequest->current_price,
            'current_price_currency'   => $productRequest->current_price_currency,
            'current_supplier'         => $productRequest->current_supplier,
            'current_supplier_contact' => $productRequest->current_supplier_contact,
            'reference1'               => $productRequest->reference1,
            'reference2'               => $productRequest->reference2,
            'reference3'               => $productRequest->reference3,
            'reference4'               => $productRequest->reference4,
            'remarks'                  => $productRequest->remarks,
            'state'                    => $productRequest->currentStateLabel(),
            'reason_closed'            => $productRequest->reason_closed ? ProductRequest::$reasonsClosed[$productRequest->reason_closed] : null,
            'closed_at'                => $productRequest->closed_at ? $productRequest->closed_at->format('d-M-y H:i:s') : null,
            'created_at'               => $productRequest->created_at->format('d-M-y H:i:s'),
            'updated_at'               => $productRequest->updated_at->format('d-M-y H:i:s'),
        ];

    }
}