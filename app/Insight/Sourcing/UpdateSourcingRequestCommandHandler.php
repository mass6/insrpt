<?php namespace Insight\Sourcing;

use Carbon\Carbon;
use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\Sourcing\Exceptions\SourcingRequestFormException;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class UpdateSourcingRequestCommandHandler
 * @package Insight\Sourcing
 */
class UpdateSourcingRequestCommandHandler implements CommandHandler
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
        $this->events = ['Updated'];
    }

    /**
     * Update the Sourcing Request
     *
     * @param $command
     * @return mixed
     * @throws SourcingRequestFormException
     */
    public function handle($command)
    {

        $sourcingRequest = $command->sourcingRequest;
        $sourcingRequestBeforeUpdate = clone($sourcingRequest);
        $assignedUserIdBeforeUpdate = $sourcingRequest->assigned_to_id;

        try {
            $sourcingRequest->update([
                'batch'                        => $command->batch,
                'received_on'                  => Carbon::createFromFormat('d-m-Y', $command->received_on),
                'customer_sku'                 => $command->customer_sku,
                'customer_product_description' => $command->customer_product_description,
                'customer_uom'                 => $command->customer_uom,
                'customer_price'               => $command->customer_price,
                'customer_price_currency'      => $command->customer_price_currency,
                'tss_sku'                      => $command->tss_sku,
                'tss_product_name'             => $command->tss_product_name,
                'tss_uom'                      => $command->tss_uom,
                'tss_buy_price'                => $command->tss_buy_price,
                'tss_buy_price_currency'       => $command->tss_buy_price_currency,
                'supplier_name'                => $command->supplier_name,
                'tss_sell_price'               => $command->tss_sell_price,
                'tss_sell_price_currency'      => $command->tss_sell_price_currency,
                'updated_by_id'                => (int) $command->user->id,
                'assigned_to_id'               => (int) $command->assigned_to_id ?: null,
                'status'                       => $command->status,
                'reason_closed'                => $command->reason_closed,
                'remarks'                      => $command->remarks,
            ]);

            // If update includes a comment, set 'Commented' event.
            if ($sourcingRequest->remarks) {
                $this->events[] = 'Commented';
            }

            // If request was assigned, set 'Assigned' event and remove 'Commented' if it exists.
            if ((int) $sourcingRequest->assigned_to_id !== (int) $assignedUserIdBeforeUpdate) {
                $this->events[] = 'Assigned';
                if (($key = array_search('Commented', $this->events)) !== false) unset($this->events[$key]);
            }

            // If request was closed, set appropriate event based on the reason_closed field
            if ($sourcingRequest->status !== $sourcingRequestBeforeUpdate->status) {
                if ($sourcingRequest->status == 'CLS') {
                    if ($sourcingRequest->reason_closed == 'COM') {
                        $this->events[] = 'Completed';
                    } else {
                        $this->events[] = 'Closed';
                    }
                }
            }

            $this->addActivityComment($command, $sourcingRequest);

            $this->raiseEvents($sourcingRequest, $this->events);

        } catch (\Exception $e) {
            throw new SourcingRequestFormException('Error updating sourcing request', $e->getMessage());
        }

        $this->dispatchEventsFor($sourcingRequest);

        return $sourcingRequest;
    }

    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $sourcingRequest
     * @return mixed
     */
    protected function  addActivityComment($command, $sourcingRequest)
    {
        $activityComment = $this->generateActivityCommentText($command, $sourcingRequest);

        $comment = $this->execute(new AddNewCommentCommand(
            $sourcingRequest,
            $command->user->id,
            $activityComment
        ));

        return $comment;
    }

    /**
     * Generates the history comment text based on the update activity type
     *
     * @param $command
     * @param $sourcingRequest
     * @return string
     */
    protected function generateActivityCommentText($command, $sourcingRequest)
    {
        $comment = "Sourcing Request was updated by {$command->user->name()}.";

        if (in_array('Completed', $this->events))
            $comment = "Sourcing Request was completed by {$command->user->name()}";

        if (in_array('Closed', $this->events))
            $comment = "Sourcing Request was closed by {$command->user->name()}. ({$sourcingRequest->reasonClosedName()})";

        if (in_array('Assigned', $this->events))
            $comment = "Sourcing Request was updated by {$command->user->name()} and assigned to {$sourcingRequest->assignedTo->name()} for {$sourcingRequest->statusName()}.";

        if (in_array('StatusChanged', $this->events))
            $comment = "Sourcing Request status was changed to {$sourcingRequest->statusName()} by {$command->user->name()}.";

        if (!empty($sourcingRequest->remarks)) {
            $comment .= '||' . $sourcingRequest->remarks;
        }

        return $comment;
    }

}