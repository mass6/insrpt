<?php

namespace Insight\ProductRequests\Commands;

use Insight\Comments\AddNewCommentCommand;
use Insight\Core\CommandBus;
use Insight\Core\RaisableTrait;
use Insight\ProductRequests\Events\ProductRequestWasClosedInBulk;
use Insight\ProductRequests\Events\ProductRequestWasCompletedInBulk;
use Insight\ProductRequests\Events\ProductRequestWasDeletedInBulk;
use Insight\ProductRequests\Events\ProductRequestWasSubmittedForCataloguingInBulk;
use Insight\ProductRequests\Events\ProductRequestWasSubmittedForDeploymentInBulk;
use Insight\ProductRequests\Events\ProductRequestWasSubmittedForPricingInBulk;
use Insight\ProductRequests\Events\ProductRequestWasSubmittedForSourcingInBulk;
use Insight\ProductRequests\Events\ProductRequestWasSubmittedInBulk;
use Insight\ProductRequests\ProductRequest;
use Laracasts\Commander\CommandHandler;
use Laracasts\Commander\Events\DispatchableTrait;

/**
 * Class ApplyProductRequestBulkTransitionsCommandHandler
 * @package Insight\Sourcing
 */
class ApplyProductRequestBulkTransitionsCommandHandler implements CommandHandler
{

    use CommandBus, DispatchableTrait, RaisableTrait;

    /**
     * @var array
     */
    private $events = [];

    private $product_requests = [];

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

        $requestIds = $command->requestIds;
        $transition = $command->transition;

        foreach ($requestIds as $id) {
            $productRequest = ProductRequest::where('request_id', $id)->first();

            if ($productRequest->can($transition)) {
                $productRequest->apply($transition);
                $productRequest->save();
                $this->product_requests[] = $productRequest;
                $this->addActivityComment($command, $productRequest);
            }

        }

        $staticProductRequest = new ProductRequest();

        if ($transition === 'submit_request') {
            $this->event[] = 'SubmittedInBulk';
            $staticProductRequest->raise(new ProductRequestWasSubmittedInBulk($this->product_requests));
        }
        if ($transition === 'delete_draft') {
            $this->event[] = 'DeletedInBulk';
            $staticProductRequest->raise(new ProductRequestWasDeletedInBulk($this->product_requests));
        }
        if ($transition === 'submit_for_sourcing') {
            $this->event[] = 'SubmittedForSourcingInBulk';
            $staticProductRequest->raise(new ProductRequestWasSubmittedForSourcingInBulk($this->product_requests));
        }
        if ($transition === 'submit_for_pricing') {
            $this->event[] = 'SubmittedForPricingInBulk';
            $staticProductRequest->raise(new ProductRequestWasSubmittedForPricingInBulk($this->product_requests));
        }
        if ($transition === 'submit_for_cataloguing') {
            $this->event[] = 'SubmittedForCataloguingInBulk';
            $staticProductRequest->raise(new ProductRequestWasSubmittedForCataloguingInBulk($this->product_requests));
        }
        if ($transition === 'submit_for_deployment') {
            $this->event[] = 'SubmittedForDeploymentInBulk';
            $staticProductRequest->raise(new ProductRequestWasSubmittedForDeploymentInBulk($this->product_requests));
        }
        if ($transition === 'complete') {
            $this->event[] = 'CompletedInBulk';
            $staticProductRequest->raise(new ProductRequestWasCompletedInBulk($this->product_requests));
        }
        if ($transition === 'close') {
            $this->event[] = 'ClosedInBulk';
            $staticProductRequest->raise(new ProductRequestWasClosedInBulk($this->product_requests));
        }

        $this->dispatchEventsFor($staticProductRequest);

        return $this->product_requests;
    }

    /**
     * Persists an associated activity comment.
     *
     * @param $command
     * @param $productRequest
     * @return mixed
     */
    protected function  addActivityComment($command, $productRequest)
    {
        $activityComment = $this->generateActivityCommentText($command, $productRequest);

        $comment = $this->execute(new AddNewCommentCommand(
            $productRequest,
            $command->user->id,
            $activityComment
        ));

        return $comment;
    }

    /**
     * Generates the history comment text based on the update activity type
     *
     * @param $command
     * @param $productRequest
     * @return string
     */
    protected function generateActivityCommentText($command, $productRequest)
    {
        $comment = '';

        if ($productRequest->getState() === 'REV')
            $comment = "Product Request was submitted for review by {$command->user->name()}.";

        if ($productRequest->getState() === 'CLS')
            $comment = "Product Request was closed by {$command->user->name()}.";

        if ($productRequest->getState() === 'SRC')
            $comment = "Product Request was submitted for sourcing by {$command->user->name()}.";

        if ($productRequest->getState() === 'PRP')
            $comment = "Product Request was submitted for pricing and proposal drafting by {$command->user->name()}.";

        if ($productRequest->getState() === 'CAT')
            $comment = "Product Request was submitted for cataloguing by {$command->user->name()}.";

        if ($productRequest->getState() === 'DEP')
            $comment = "Product Request was submitted for deployment by {$command->user->name()}.";

        if ($productRequest->getState() === 'COM')
            $comment = "Product Request was completed by {$command->user->name()}.";

        return $comment;
    }

}