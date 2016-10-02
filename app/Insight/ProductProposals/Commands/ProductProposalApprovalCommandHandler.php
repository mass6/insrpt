<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class ProductProposalApprovalCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class ProductProposalApprovalCommandHandler implements CommandHandler
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
     * Create the Sourcing Request
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $productProposal = $command->productProposal;

        try {

            $productProposal->updated_by_id = $command->user->id;
            $productProposal->remarks = $command->remarks;
            if ($productProposal->can($command->transition)){
                $productProposal->apply($command->transition);
            }
            if ($command->transition === 'approve') {
                $this->events[] = 'Approved';
            }
            if ($command->transition === 'reject') {
                $this->events[] = 'Rejected';
                $productProposal->remarks .= $command->reject_reason;
            }
            if ($command->transition === 'recall_proposal') {
                $this->events[] = 'Recalled';
            }
            $productProposal->save();

            $this->addActivityComment($command, $productProposal);

            $this->raiseEvents($productProposal, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($productProposal);

        return $productProposal;
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
        if ($command->transition === 'approve') {
            $comment = "Product Proposal {$productProposal->proposal_id} was approved by {$command->user->name()}.";
        }

        if ($command->transition === 'reject') {
            $comment = "Product Proposal {$productProposal->proposal_id} was rejected by {$command->user->name()}.";
            $comment .= '||' . $command->reject_reason;
        }

        if ($command->transition === 'recall_proposal') {
            $comment = "Product Proposal {$productProposal->proposal_id} was recalled by {$command->user->name()}.";
        }

        if (!empty($productProposal->remarks)) {
            $comment .= '||' . $command->remarks;
        }

        return $comment;
    }

}