<?php

namespace Insight\ProductProposals\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;


/**
 * Class AutomaticProposalApprovalCommandHandler
 * @package Insight\ProductProposals\Commands
 */
class AutomaticProposalApprovalCommandHandler implements CommandHandler
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

            $productProposal->apply('approve');
            $productProposal->auto_approved = true;
            $productProposal->save();

            $this->events[] = ['Approved', 'AutomaticallyApproved'];

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
            $adminUserId = 1,
            $activityComment
        ));
        $this->execute(new AddNewCommentCommand(
            $productProposal->productRequest,
            $adminUserId = 1,
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

        return  $comment = "This proposal ({$productProposal->proposal_id}) has been auto approved according to the configured {$productProposal->company->settings()->timeoutWindow()} hour
        approval setting. All product proposals that have not been approved or rejected within this period will be automatically approved by the system";
    }

}