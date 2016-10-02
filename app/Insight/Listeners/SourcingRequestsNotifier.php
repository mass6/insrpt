<?php

namespace Insight\Listeners;

use Insight\Mailers\SourcingRequestsMailer;
use Insight\Sourcing\Events\SourcingRequestCommentWasCreated;
use Insight\Sourcing\Events\SourcingRequestWasAssigned;
use Insight\Sourcing\Events\SourcingRequestWasCommented;
use Insight\Sourcing\Events\SourcingRequestWasImported;

class SourcingRequestsNotifier extends EventListener
{

    /**
     * @var \Insight\Mailers\SourcingRequestsMailer
     */
    private $mailer;

    /**
     * @param SourcingRequestsMailer $mailer
     */
    public function __construct(SourcingRequestsMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * Sends an email to the newly assigned user.
     *
     * @param SourcingRequestWasAssigned $event
     */
    public function whenSourcingRequestWasAssigned(SourcingRequestWasAssigned $event)
    {
        $sourcingRequest = $event->sourcingRequest;
        $emailRecipient = $sourcingRequest->assignedTo->email;

        $data = [
            'sourcingRequest' => $sourcingRequest,
            'assignedUser'    => $sourcingRequest->assignedTo->name(),
            'assignedBy'      => $sourcingRequest->updatedBy->name(),
            'customerName'    => $sourcingRequest->customer->name,
            'remarks'         => $sourcingRequest->remarks,
        ];

        $this->mailer->sendRequestWasAssignedTo($emailRecipient, $data);

    }


    /**
     * Sends an email to the assigned user when souring requests were imported.
     *
     * @param SourcingRequestWasImported $event
     */
    public function whenSourcingRequestWasImported(SourcingRequestWasImported $event)
    {
        $sourcingRequests = $event->sourcingRequests;
        $commonRequest = head($sourcingRequests);
        $emailRecipient = $commonRequest->assignedTo->email;

        $data = [
            'assignedUser'     => $commonRequest->assignedTo->name(),
            'assignedBy'       => $commonRequest->createdBy->name(),
            'customerName'     => $commonRequest->customer->name,
            'batch'            => $commonRequest->batch,
            'sourcingRequests' => $sourcingRequests,
        ];

        $this->mailer->sendRequestsWereImported($emailRecipient, $data);

    }

    /**
     * Sends an email to the assigned user when someone else comments on the request.
     *
     * @param SourcingRequestWasCommented $event
     */
    public function whenSourcingRequestWasCommented(SourcingRequestWasCommented $event)
    {
        $sourcingRequest = $event->sourcingRequest;

        $usersToBeNotified = [];
        if ($sourcingRequest->updated_by_id !== $sourcingRequest->assigned_to_id) {
            array_push($usersToBeNotified, $sourcingRequest->assignedTo);
        }
        $usersToBeNotified = array_unique($usersToBeNotified);

        if ($usersToBeNotified) {

            foreach ($usersToBeNotified as $user) {
                $data = [
                    'recipientName'                => $user->name(),
                    'commenter'                    => $sourcingRequest->updatedBy->name(),
                    'sourcingRequest'              => $sourcingRequest,
                    'customerName'                 => $sourcingRequest->customer->name,
                    'customer_sku'                 => $sourcingRequest->customer_sku,
                    'customer_product_description' => $sourcingRequest->customer_product_description,
                    'comment'                      => $sourcingRequest->remarks,
                    'commentDate'                  => $sourcingRequest->updated_at->format('l, d M Y g:i:s a'),
                ];

                $this->mailer->sendCommentWasCreated($user->email, $data);
            }
        }


    }
}