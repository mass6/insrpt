<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductDefinitions\ProductAttachment;
use Insight\ProductDefinitions\ProductImage;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class UpdateProductProposalCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class UpdateProductProposalCommandHandler implements CommandHandler
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
        $this->events = [];
    }

    /**
     * Update the Product Proposal
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {

        $productProposal = $command->productProposal;

        try {

            $productProposal->update([
                'product_name'              => $command->product_name,
                'product_description'       => $command->product_description,
                'uom'                       => $command->uom,
                'volume'                    => $command->volume,
                'sku'                       => $command->sku,
                'price'                     => $command->price,
                'price_currency'            => $command->price_currency,
                'display_quotation_details' => (bool) $command->display_quotation_details,
                'remarks'                   => $command->remarks,
                'updated_by_id'             => (int) $command->user->id,
            ]);
            $productProposal->apply($command->transition);

            $this->saveAttachments($productProposal, $command->attachments);

            if ($command->transition === 'update') {
                $this->events[] = ['Updated'];
            }
            if ($command->transition === 'submit_proposal') {
                $this->events[] = ['Submitted'];
            }
            if ($command->transition === 'recall_proposal') {
                $this->events[] = ['Recalled'];
            }
            if ($command->transition === 'delete') {
                $this->events[] = ['Deleted'];
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
     * @param $productProposal
     * @param $attachments
     */
    private function saveProductImages($productProposal, $attachments)
    {
        foreach ($attachments as $name => $id) {
            if (!is_null($id)) {
                $image = ProductImage::findOrFail($id);
                $productProposal->images()->save($image);
            }
        }

        return $productProposal;
    }

    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $productProposal
     * @return mixed
     */
    protected function addActivityComment($command, $productProposal)
    {
        $activityComment = $this->generateActivityCommentText($command, $productProposal);

        if ($activityComment) {
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
        }

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
        $comment = '';
        if ($command->transition === 'save_draft')
            $comment = "Product Proposal draft {$productProposal->proposal_id} was updated by {$command->user->name()}.";

        if ($command->transition === 'update')
            $comment = "Product Proposal {$productProposal->proposal_id} was updated by {$command->user->name()}.";

        if ($command->transition === 'recall_proposal')
            $comment = "Product Proposal {$productProposal->proposal_id} was recalled by {$command->user->name()}.";

        if ($command->transition === 'delete')
            $comment = "Product Proposal {$productProposal->proposal_id} was cancelled by {$command->user->name()}.";

        if (!empty($productProposal->remarks) && $command->transition !== 'submit_proposal') {
            $comment .= '||' . $productProposal->remarks;
        }

        return $comment;
    }

}