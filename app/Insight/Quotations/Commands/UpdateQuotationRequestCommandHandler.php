<?php

namespace Insight\Quotations\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class UpdateQuotationRequestCommandHandler
 * @package Insight\Quotations\Commands
 */
class UpdateQuotationRequestCommandHandler implements CommandHandler
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
     * Create the Quotation Request
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        $quotationRequest = $command->quotationRequest;
        if ($quotationRequest->can($command->transition)){
            $quotationRequest->apply($command->transition);
        }

        try {
            $quotationRequest->update([
                'supplier_id'      => $command->supplier_id ?: null,
                'send_to_supplier' => $command->send_to_supplier,
                'message'          => $command->message,
                'updated_by_id'    => $command->user->id,
            ]);
            $this->assignSupplierToQuotations($quotationRequest, $command);

            if (in_array($command->transition, ['submit', 'save_submitted'])) {
                $this->events[] = 'submitted';
                if ($command->send_to_supplier) {
                    $this->events[] = 'sent';
                }
            }
            if ($command->transition == 'receive_quotation') {
                $this->events[] = 'Received';
            }
            $quotationRequest->ccSender = $command->ccSender;

            $this->addActivityComment($command, $quotationRequest);
            $this->raiseEvents($quotationRequest, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($quotationRequest);

        return $quotationRequest;
    }

    /**
     * @param $quotationRequest
     * @param $command
     */
    private function assignSupplierToQuotations($quotationRequest, $command)
    {
        foreach ($quotationRequest->quotations as $quotation) {
            $quotation->supplier_id = $command->supplier_id;
            $quotation->save();
        }
    }

    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $quotationRequest
     * @return mixed
     */
    protected function  addActivityComment($command, $quotationRequest)
    {
        $activityComment = $this->generateActivityCommentText($command, $quotationRequest);

        $comment = $this->execute(new AddNewCommentCommand(
            $quotationRequest,
            $command->user->id,
            $activityComment
        ));

        return $comment;
    }

    /**
     * Generates the history comment text based on the activity type
     *
     * @param $command
     * @param $quotationRequest
     * @return string
     */
    protected function generateActivityCommentText($command, $quotationRequest)
    {
        $comment = '';
        if ($quotationRequest->getState() === 'SUB')
            $comment = "Quotation Request was submitted by {$command->user->name()}.";

        if ($quotationRequest->getState() === 'DRA')
            $comment = "Quotation Request was updated by {$command->user->name()}.";

        if ($command->transition === 'receive_quotation')
            $comment = "Quotation(s) have been marked as received from the supplier by {$command->user->name()}.";

        return $comment ?: "Quotation Request was updated by {$command->user->name()}.";;
    }

}