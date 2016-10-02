<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class CancelProductProposalCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class CancelProductProposalCommandHandler implements CommandHandler
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
        $this->events = ['Deleted'];
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
            if ($productProposal->can('delete')) {
                $productProposal->apply('delete');
                $productProposal->save();
            }

            $this->addActivityComment($command, $productProposal);
            $this->raiseEvents($productProposal, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($productProposal);
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
        $comment = "Product Proposal {$productProposal->proposal_id} was cancelled by {$command->user->name()}.";

        if (!empty($productProposal->remarks)) {
            $comment .= '||' . $productProposal->remarks;
        }

        return $comment;
    }

}