<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class AssignProductProposalCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class AssignProductProposalCommandHandler
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
        $this->events = ['Assigned'];
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
        $this->raiseEvents($productProposal, $this->events);
        $this->dispatchEventsFor($productProposal);
        $this->addActivityComment($command, $productProposal);

        return $productProposal;
    }


    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $productProposal
     *
     * @return mixed
     */
    protected function addActivityComment($command, $productProposal)
    {
        $activityComment = $this->generateActivityCommentText($productProposal);

        $this->execute(new AddNewCommentCommand(
            $productProposal,
            $productProposal->updated_by_id,
            $activityComment
        ));
        $this->execute(new AddNewCommentCommand(
            $productProposal->productRequest,
            $productProposal->updated_by_id,
            $activityComment
        ));

        return;
    }

    /**
     * Generates the history comment text based on the update activity type
     *
     * @param $productProposal
     * @return string
     */
    protected function generateActivityCommentText($productProposal)
    {
        if (!$productProposal->num_approvals) {
            $comment = "Product Proposal {$productProposal->proposal_id} was submitted to {$productProposal->assignedTo->name()} for approval by {$productProposal->updatedBy->name()}.";
        } else {
            $comment = "Product Proposal {$productProposal->proposal_id} was assigned to {$productProposal->assignedTo->name()} for approval by {$productProposal->updatedBy->name()}.";
        }

        if (!empty($productProposal->remarks)) {
            $comment .= '||' . $productProposal->remarks;
        }

        return $comment;
    }

}