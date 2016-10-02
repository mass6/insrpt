<?php namespace Insight\Mailers;

/**
 * Insight Client Management Portal:
 * Date: 8/16/14
 * Time: 4:23 PM
 */
class ProductRequestsMailer extends Mailer
{

    /**
     * @param $creatorEmail
     * @param $requesterEmail
     * @param array $data
     */
    public function sendProductRequestWasCreated($creatorEmail, $requesterEmail, $data = [])
    {
        $subject = 'Product Request Created: '
            . $data['productRequest']['request_id'] . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');

        $view = 'emails.product-requests.created';

        if($creatorEmail !== $requesterEmail) {
            $this->sendCc($requesterEmail, $creatorEmail, $subject, $view, $data);
        } else {
            $this->sendTo($creatorEmail, $subject, $view, $data);
        }
    }/**
     * @param $creatorEmail
     * @param $requesterEmail
     * @param array $data
     */
    public function sendProductRequestListWasUploaded($creatorEmail, $requesterEmail, $data = [])
    {
        $subject = 'Product Request List Uploaded: '
            . str_limit($data['productRequestList']['name'], $limit = 240, $end = '...');

        $view = 'emails.product-requests.uploaded';

        if($creatorEmail !== $requesterEmail) {
            $this->sendCc($requesterEmail, $creatorEmail, $subject, $view, $data);
        } else {
            $this->sendTo($creatorEmail, $subject, $view, $data);
        }
    }
    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductRequestWasReassignedToRequester($emailRecipient, $data = [])
    {
        $subject = 'Your Product Request requires further input: ' . $data['productRequest']['request_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-requests.reassigned_to_requester';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }
    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductRequestWasCompleted($emailRecipient, $data = [])
    {
        $subject = 'Product Request Completed: ' . $data['productRequest']['request_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-requests.completed';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }
    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductRequestWasClosed($emailRecipient, $data = [])
    {
        $subject = 'Product Request Closed: ' . $data['productRequest']['request_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-requests.closed';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }
    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductRequestWasReopened($emailRecipient, $data = [])
    {
        $subject = 'Product Request Reopened: ' . $data['productRequest']['request_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-requests.reopened';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }

    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductProposalWasAssignedTo($emailRecipient, $data = [])
    {
        $subject = 'Product Proposal is awaiting your approval: ' . $data['proposal_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-proposals.assigned';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }

    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductProposalWasRecalledTo($emailRecipient, $data = [])
    {
        $subject = 'Product Proposal Recalled: ' . $data['proposal_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-proposals.recalled';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }

    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendProductProposalWasRejectedTo($emailRecipient, $data = [])
    {
        $subject = 'Product Proposal Rejected: ' . $data['proposal_id']  . ' '
            . str_limit($data['productRequest']['product_description'], $limit = 215, $end = '...');
        $view = 'emails.product-proposals.rejected';
        $this->sendTo($emailRecipient, $subject, $view, $data);
    }
}