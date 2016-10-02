<?php

namespace Insight\ProductRequests\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductDefinitions\ProductAttachment;
use Insight\ProductDefinitions\ProductImage;
use Insight\ProductRequests\Exceptions\ProductRequestFormException;
use Insight\ProductRequests\ProductRequest;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class UpdateProductRequestCommandHandler
 * @package Insight\ProductRequests\Commands
 */
class UpdateProductRequestCommandHandler implements CommandHandler
{

    use CommandBus, DispatchableTrait, RaisableTrait;

    /**
     * @var array
     */
    private $events = [];

    /**
     * Handle the command
     *
     * @param $command
     * @return mixed
     * @throws ProductRequestFormException
     */
    public function handle($command)
    {
        $productRequest = $command->productRequest;

        try {

            $this->updateRequest($command, $productRequest);

            $this->setEvents($command, $productRequest);

            $this->addActivityComment($command, $productRequest);

            $this->fireEvents($productRequest);

        } catch (\Exception $e) {
            throw new ProductRequestFormException('Error updating product request', $e->getMessage());
        }


        return $productRequest;
    }

    /**
     * @param $command
     * @param $productRequest
     */
    private function updateRequest($command, ProductRequest $productRequest)
    {
        $this->persistChangesToDb($command, $productRequest);

        $this->saveAttachments($productRequest, $command->attachments);

        $productRequest->setAssociatedPortalContracts($command->contracts_assigned);

        $productRequest->apply($command->transitionName);
    }

    /**
     * @param $command
     * @param $productRequest
     */
    private function persistChangesToDb($command, ProductRequest $productRequest)
    {
        $productRequest->update([
            'product_description'       => $command->product_description,
            'uom'                       => $command->uom,
            'category'                  => $command->category,
            'purchase_recurrence'       => $command->purchase_recurrence,
            'first_time_order_quantity' => $command->first_time_order_quantity,
            'volume_requested'          => $command->volume_requested,
            'sku'                       => $command->sku,
            'current_price'             => $command->current_price,
            'current_price_currency'    => $command->current_price_currency,
            'current_supplier'          => $command->current_supplier,
            'current_supplier_contact'  => $command->current_supplier_contact,
            'reference1'                => $command->reference1,
            'reference2'                => $command->reference2,
            'reference3'                => $command->reference3,
            'reference4'                => $command->reference4,
            'cataloguing_item_code'     => $command->cataloguing_item_code,
            'cataloguing_product_name'  => $command->cataloguing_product_name,
            'remarks'                   => $command->remarks,
            'updated_by_id'             => (int) $command->user->id,
            'reason_closed'             => $command->reason_closed,
        ]);
    }

    /**
     * @param $productRequest
     * @param $attachments
     */
    private function saveAttachments(ProductRequest $productRequest, $attachments)
    {
        foreach ($attachments as $key => $data) {
            if ($data['type'] === 'image' && !is_null($data['id'])) {
                $image = ProductImage::findOrFail($data['id']);
                $image->user_id = $productRequest->created_by_id;
                $productRequest->images()->save($image);
            } elseif ($data['type'] === 'attachment' && !is_null($data['id'])) {
                $attachment = ProductAttachment::findOrFail($data['id']);
                $attachment->user_id = $productRequest->created_by_id;
                $productRequest->attachments()->save($attachment);
            }
        }
    }


    /**
     * @param $command
     * @param $productRequest
     */
    private function setEvents($command, $productRequest)
    {
        // If update includes a comment, set 'Commented' event.
        if ($productRequest->remarks) {
            $this->events[] = 'Commented';
        }
        if ($command->transitionName !== 'close') {
            $this->events[] = 'Updated';
        }
        if ($command->transitionName === 'submit_request') {
            $this->events[] = 'SubmittedForReview';
        }
        if ($command->transitionName === 'reassign_to_requester') {
            $this->events[] = 'ReassignedToRequester';
        }
        if ($command->transitionName === 'submit_for_sourcing') {
            $this->events[] = 'SubmittedForSourcing';
        }
        if ($command->transitionName === 'submit_for_pricing') {
            $this->events[] = 'SubmittedForPricing';
        }
        if ($command->transitionName === 'complete') {
            $this->events[] = 'Completed';
        }
        if ($command->transitionName === 'close') {
            $this->events[] = 'Closed';
        }
        if ($command->transitionName === 'reopen') {
            $this->events[] = 'Reopened';
        }
    }


    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $productRequest
     * @return mixed
     */
    protected function  addActivityComment($command, $productRequest)
    {
        $activityComment = $this->generateActivityCommentText($command, $productRequest);

        $comment = $this->execute(new AddNewCommentCommand(
            $productRequest,
            $command->user->id,
            $activityComment
        ));

        return $comment;
    }


    /**
     * Generates the history comment text based on the update activity type
     *
     * @param $command
     * @param $productRequest
     * @return string
     */
    protected function generateActivityCommentText($command, $productRequest)
    {
        $comment = "Product Request was updated by {$command->user->name()}.";

        if ($command->transitionName === 'save_draft')
            $comment = "Product Request draft was saved by {$command->user->name()}.";

        if ($command->transitionName === 'submit_request')
            $comment = "Product Request was submitted for review by {$command->user->name()}.";

        if ($command->transitionName === 'reassign_to_requester')
            $comment = "Product Request was reassigned to {$productRequest->requestedBy->name()} by {$productRequest->updatedBy->name()} for additional input.";

        if ($command->transitionName === 'submit_for_sourcing')
            $comment = "Product Request was submitted for sourcing by {$productRequest->updatedBy->name()}.";

        if ($command->transitionName === 'submit_for_pricing')
            $comment = "Product Request was submitted for pricing by {$productRequest->updatedBy->name()}.";

        if ($command->transitionName === 'revert_for_review')
            $comment = "Product Request was reassigned for procurement review by {$productRequest->updatedBy->name()}.";

        if ($command->transitionName === 'revert_for_cataloguing')
            $comment = "Product Request was reassigned for cataloguing by {$productRequest->updatedBy->name()}.";

        if ($command->transitionName === 'complete')
            $comment = "Product Request was has been completed by {$productRequest->updatedBy->name()}.";

        if ($command->transitionName === 'reopen')
            $comment = "Product Request was has been reopened by {$productRequest->updatedBy->name()}.";

        if ($command->transitionName === 'close') {
            if ($command->reason_closed === 'DUP')
                $comment = "Product Request was has been closed by {$productRequest->updatedBy->name()} because it was a duplicate request, or the same or comparable product already exists.";

            if ($command->reason_closed === 'WNS')
                $comment = "Product Request was has been closed by {$productRequest->updatedBy->name()} because this item will not be sourced.";

        }

        if (!empty($productRequest->remarks)) {
            $comment .= '||' . $productRequest->remarks;
        }

        return $comment;
    }


    /**
     * @param $productRequest
     */
    private function fireEvents($productRequest)
    {
        $this->raiseEvents($productRequest, $this->events);

        $this->dispatchEventsFor($productRequest);
    }

}
