<?php

namespace Insight\Listeners;

use Insight\Mailers\ProductRequestsMailer;
use Insight\ProductProposals\Events\ProductProposalWasAssigned;
use Insight\ProductProposals\Events\ProductProposalWasRecalled;
use Insight\ProductProposals\Events\ProductProposalWasRejected;
use Insight\ProductProposals\ProductProposal;
use Insight\ProductProposals\ProductProposalStateTransition;
use Insight\ProductRequests\ProductRequest;
use Insight\Users\User;

/**
 * Class ProductProposalsNotifier
 * @package Insight\Listeners
 */
class ProductProposalsNotifier extends EventListener
{

    /**
     * @var ProductRequestsMailer
     */
    private $productRequestsMailer;

    /**
     * @param ProductRequestsMailer $productRequestsMailer
     */
    public function __construct(ProductRequestsMailer $productRequestsMailer)
    {
        $this->productRequestsMailer = $productRequestsMailer;
    }


    /**
     * @param ProductProposalWasAssigned $event
     */
    public function whenProductProposalWasAssigned(ProductProposalWasAssigned $event)
    {

        $productProposal = $event->productProposal;
        $productRequest = $productProposal->productRequest;
        $emailRecipient = $productProposal->assignedTo->email;
        $timeoutWindow = $productProposal->company->settings()->timeoutWindow();
        $productProposalArray = $productProposal->toArray();
        foreach (ProductProposal::$currencyFields as $price) {
            $productProposalArray[$price] = $productProposal->{$price};
        }
        $productRequestArray = $productRequest->toArray();
        foreach (ProductRequest::$currencyFields as $price) {
            $productRequestArray[$price] = $productRequest->{$price};
        }

        $data = [
            'proposal'       => $productProposalArray,
            'productRequest' => $productRequestArray,
            'requestedBy'    => $productRequest->requestedBy->name(),
            'proposal_id'    => $productProposal->proposal_id,
            'assignedTo'     => $productProposal->assignedTo->name(),
            'timeoutWindow'  => $timeoutWindow,
        ];

        $this->productRequestsMailer->sendProductProposalWasAssignedTo($emailRecipient, $data);
    }

    /**
     * @param ProductProposalWasRecalled $event
     */
    public function whenProductProposalWasRecalled(ProductProposalWasRecalled $event)
    {

        $productProposal = $event->productProposal;
        $productRequest = $productProposal->productRequest;
        $emailRecipient = $productRequest->requestedBy->email;
        $productProposalArray = $productProposal->toArray();
        foreach (ProductProposal::$currencyFields as $price) {
            $productProposalArray[$price] = $productProposal->{$price};
        }
        $productRequestArray = $productRequest->toArray();
        foreach (ProductRequest::$currencyFields as $price) {
            $productRequestArray[$price] = $productRequest->{$price};
        }

        $data = [
            'proposal'       => $productProposalArray,
            'productRequest' => $productRequestArray,
            'requestedBy'    => $productRequest->requestedBy->name(),
            'proposal_id'    => $productProposal->proposal_id,
            'recalledBy'     => $productProposal->updatedBy->name(),
        ];

        $this->productRequestsMailer->sendProductProposalWasRecalledTo($emailRecipient, $data);
    }

    /**
     * @param ProductProposalWasRejected $event
     */
    public function whenProductProposalWasRejected(ProductProposalWasRejected $event)
    {

        $productProposal = $event->productProposal;
        $productRequest = $productProposal->productRequest;
        $submittedBy = User::findOrFail(ProductProposalStateTransition::where('product_proposal_id', $productProposal->id)->where('event', 'submit_proposal')->get()->last()->user_id);
        $emailRecipient = $submittedBy->email;
        $productProposalArray = $productProposal->toArray();
        $productRequestArray = $productRequest->toArray();

        $data = [
            'proposal'       => $productProposalArray,
            'productRequest' => $productRequestArray,
            'submittedBy'    => $submittedBy->name(),
            'proposal_id'    => $productProposal->proposal_id,
            'rejectedBy'     => $productProposal->updatedBy->name(),
        ];

        $this->productRequestsMailer->sendProductProposalWasRejectedTo($emailRecipient, $data);
    }


}