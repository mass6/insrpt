<?php namespace Insight\Sourcing;

use Carbon\Carbon;
use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class AddNewSourcingRequestCommandHandler
 * @package Insight\Sourcing
 */
class AddNewSourcingRequestCommandHandler implements CommandHandler
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
            $request = new SourcingRequest([
                'customer_id'                  => $command->customer_id,
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
                'remarks'                      => $command->remarks,
            ]);

            $sourcingRequest = $command->user->sourcingRequests()->save($request);

            if ($sourcingRequest->assignedTo) {
                $this->events[] = 'Assigned';
            }

            $this->addActivityComment($command, $sourcingRequest);

            $this->raiseEvents($sourcingRequest, $this->events);

        } catch (\Exception $e) {
            return $e;
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
        $comment = "Sourcing Request was created by {$command->user->name()}.";

        if (in_array('Assigned', $this->events))
            $comment = "Sourcing Request was created by {$command->user->name()} and assigned to {$sourcingRequest->assignedTo->name()} for {$sourcingRequest->statusName()}.";

        if (!empty($sourcingRequest->remarks)) {
            $comment .= '||' . $sourcingRequest->remarks;
        }

        return $comment;
    }

}