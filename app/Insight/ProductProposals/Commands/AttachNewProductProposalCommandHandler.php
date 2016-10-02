<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductDefinitions\ProductAttachment;
use Insight\ProductDefinitions\ProductImage;
use Insight\ProductProposals\ProductProposal;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class AttachNewProductProposalCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class AttachNewProductProposalCommandHandler implements CommandHandler
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
            $productProposal = new ProductProposal([
                'request_id'                => (int) $command->productRequest->id,
                'quotation_id'              => (int) $command->quotation_id,
                'created_by_id'             => (int) $command->user->id,
                'company_id'                => (int) $command->company_id,
                'product_name'              => $command->product_name,
                'product_description'       => $command->product_description,
                'uom'                       => $command->uom,
                'volume'                    => $command->volume,
                'sku'                       => $command->sku,
                'price'                     => $command->price,
                'price_currency'            => $command->price_currency,
                'display_quotation_details' => $command->display_quotation_details,
                'remarks'                   => $command->remarks,
                'state'                     => ProductProposal::getInitialState(),
                'updated_by_id'             => (int) $command->user->id,
            ]);

            $productProposal = $command->productRequest->proposals()->save($productProposal);
            $productProposal->apply($command->transition);

            $this->saveAttachments($productProposal, $command->attachments);

            if ($command->transition === 'submit_proposal') {
                $this->events = ['Submitted'];
            }

            $this->addActivityComment($command, $productProposal);

            $this->raiseEvents($productProposal, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($productProposal);

        return $productProposal;
    }

    /**
     * @param $productProposal
     * @param $attachments
     */
    private function saveAttachments($productProposal, $attachments)
    {
        foreach ($attachments as $key => $data) {
            if ($data['type'] === 'image' && !is_null($data['id'])) {
                $image = ProductImage::findOrFail($data['id']);
                $productProposal->images()->save($image);
            } elseif ($data['type'] === 'attachment' && !is_null($data['id'])) {
                $attachment = ProductAttachment::findOrFail($data['id']);
                $productProposal->attachments()->save($attachment);
            }
        }
    }

    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $productProposal
     * @return mixed
     */
    protected function  addActivityComment($command, $productProposal)
    {
        $activityComment = $this->generateActivityCommentText($command, $productProposal);

        $this->execute(new AddNewCommentCommand(
            $productProposal,
            $command->user->id,
            $activityComment
        ));
        $this->execute(new AddNewCommentCommand(
            $productProposal->productRequest,
            $command->user->id,
            $activityComment
        ));

        return;
    }

    /**
     * Generates the history comment text based on the update activity type
     *
     * @param $command
     * @param $productProposal
     * @return string
     */
    protected function generateActivityCommentText($command, $productProposal)
    {

        $comment = "Product Proposal " . $command->transition === 'save_draft' ? 'draft ' : '' . "{$productProposal->proposal_id} was created by {$command->user->name()}.";

        if (!empty($productProposal->remarks)) {
            $comment .= '||' . $productProposal->remarks;
        }

        return $comment;
    }

}