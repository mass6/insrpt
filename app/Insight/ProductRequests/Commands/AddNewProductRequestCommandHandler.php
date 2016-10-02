<?php

namespace Insight\ProductRequests\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductDefinitions\ProductAttachment;
use Insight\ProductDefinitions\ProductImage;
use Insight\ProductRequests\ProductRequest;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class AddNewSourcingRequestCommandHandler
 * @package Insight\Sourcing
 */
class AddNewProductRequestCommandHandler implements CommandHandler
{

    use CommandBus, DispatchableTrait, RaisableTrait;

    /**
     * @var array
     */
    private $events = [];

    /**
     *
     */
    public function __construct()
    {
        $this->events = ['Created'];
    }

    /**
     * Create the Sourcing Request
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {

        try {
            $productRequest = new ProductRequest([
                'created_by_id'            => $command->user->id,
                'requested_by_id'          => $command->requester->id,
                'list_id'                  => $command->productRequestListId,
                'company_id'               => $command->user->company->id,
                'product_description'      => $command->product_description,
                'uom'                      => $command->uom,
                'category'                 => $command->category,
                'purchase_recurrence'      => $command->purchase_recurrence,
                'first_time_order_quantity'         => $command->first_time_order_quantity,
                'volume_requested'         => $command->volume_requested,
                'sku'                      => $command->sku,
                'current_price'            => $command->current_price,
                'current_price_currency'   => $command->current_price_currency,
                'current_supplier'         => $command->current_supplier,
                'current_supplier_contact' => $command->current_supplier_contact,
                'reference1'               => $command->reference1,
                'reference2'               => $command->reference2,
                'reference3'               => $command->reference3,
                'reference4'               => $command->reference4,
                'remarks'                  => $command->remarks,
                'state'                    => ProductRequest::getInitialState(),
                'updated_by_id'            => (int) $command->user->id,
            ]);

            $productRequest->apply($command->transitionName);

            $productRequest = $command->requester->productRequests()->save($productRequest);

            $this->saveAttachments($productRequest, $command->attachments);

            $productRequest->contracts()->sync($command->contracts_assigned);

            $this->addActivityComment($command, $productRequest);

            $this->raiseEvents($productRequest, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($productRequest);

        return $productRequest;
    }

    /**
     * @param $productRequest
     * @param $attachments
     */
    private function saveAttachments($productRequest, $attachments)
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
     * @param $productRequest
     * @param $attachments
     */
    private function saveProductImages($productRequest, $attachments)
    {
        foreach ($attachments as $name => $id) {
            if (!is_null($id)) {
                $image = ProductImage::findOrFail($id);
                $productRequest->images()->save($image);
            }
        }

        return $productRequest;
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
        if ($productRequest->getState() === 'DRA')
            $comment = "Product Request draft was created by {$command->user->name()}.";

        if ($productRequest->getState() === 'REV')
            $comment = "Product Request was created and submitted for review by {$command->user->name()}.";

        if (!empty($productRequest->remarks)) {
            $comment .= '||' . $productRequest->remarks;
        }

        return $comment;
    }

}