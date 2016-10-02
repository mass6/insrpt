<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductProposals\Events\ProductProposalWasApprovedInBulk;
use Insight\ProductProposals\Events\ProductProposalWasRecalledInBulk;
use Insight\ProductProposals\Events\ProductProposalWasRejectedInBulk;
use Insight\ProductProposals\ProductProposal;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class ApplyProductProposalBulkTransitionsCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class ApplyProductProposalBulkTransitionsCommandHandler implements CommandHandler
{

    use CommandBus, DispatchableTrait, RaisableTrait;

    /**
     * @var array
     */
    private $events = [];

    private $proposals = [];
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
        $proposalIds = $command->proposalIds;
        $transition = $command->transition;

        foreach ($proposalIds as $id) {
            $productProposal = ProductProposal::where('proposal_id', $id)->first();

            if ($productProposal->can($transition)) {
                $productProposal->apply($transition);
                $productProposal->save();
                $this->proposals[] = $productProposal;
                $this->addActivityComment($command, $productProposal);
            }

        }

        $staticProductProposal = new ProductProposal();
        if ($transition === 'approve') {
            $this->event[] = 'ApprovedInBulk';
            $staticProductProposal->raise(new ProductProposalWasApprovedInBulk($this->proposals));
        }
        if ($transition === 'reject') {
            $this->event[] = 'RejectedInBulk';
            $staticProductProposal->raise(new ProductProposalWasRejectedInBulk($this->proposals));
        }
        if ($transition === 'recall_proposal') {
            $this->event[] = 'RecalledInBulk';
            $staticProductProposal->raise(new ProductProposalWasRecalledInBulk($this->proposals));
        }

        $this->dispatchEventsFor($staticProductProposal);

        return $this->proposals;
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
        $comment = '';

        if ($command->transition === 'approve')
            $comment = "Product Proposal was approved by {$command->user->name()}.";

        if ($command->transition === 'reject')
            $comment = "Product Proposal was rejected by {$command->user->name()}.";

        if ($command->transition === 'recall')
            $comment = "Product Proposal was recalled by {$command->user->name()}.";

        return $comment;
    }

}