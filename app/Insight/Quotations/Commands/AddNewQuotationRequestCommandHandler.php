<?php

namespace Insight\Quotations\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\Quotations\Quotation;
use Insight\Quotations\QuotationRequest;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class AddNewQuotationRequestCommandHandler
 * @package Insight\Sourcing
 */
class AddNewQuotationRequestCommandHandler implements CommandHandler
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
     * Create the Quotation Request
     *
     * @param $command
     * @return mixed
     */
    public function handle($command)
    {
        try {
            $quotationRequest = QuotationRequest::create([
                'created_by_id' => $command->user->id,
                'company_id'    => $command->company->id,
                'updated_by_id' => $command->user->id,
            ]);

            $quotationRequest = $this->createQuotations($quotationRequest, $command);
            $this->addActivityComment($command, $quotationRequest);
            $this->raiseEvents($quotationRequest, $this->events);

        } catch (\Exception $e) {
            return $e;
        }

        $this->dispatchEventsFor($quotationRequest);

        return $quotationRequest;
    }

    /**
     * @param QuotationRequest $quotationRequest
     * @param $command
     * @return QuotationRequest
     */
    private function createQuotations(QuotationRequest $quotationRequest, $command)
    {
        foreach ($command->product_requests as $product_request) {
            $quotation = new Quotation([
                'created_by_id'          => $command->user->id,
                'product_description'    => $product_request->product_description,
                'uom'                    => $product_request->uom,
                'volume'                 => $product_request->first_time_order_quantity,
                'current_price'          => $product_request->current_price,
                'current_price_currency' => $product_request->current_price_currency,
                // default quotation data
                'suppliers_product_name' => $product_request->product_description,
                'suppliers_uom'          => $product_request->uom,
                'suppliers_quantity'     => $product_request->first_time_order_quantity,
            ]);
            $command->company->quotations()->save($quotation);
            $product_request->quotations()->save($quotation);
            $quotationRequest->quotations()->save($quotation);
        }

        return $quotationRequest;
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
        return $comment = "Quotation Request was created by {$command->user->name()}.";
    }

}