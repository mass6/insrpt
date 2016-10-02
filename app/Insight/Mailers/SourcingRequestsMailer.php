<?php namespace Insight\Mailers;

/**
 * Insight Client Management Portal:
 * Date: 8/16/14
 * Time: 4:23 PM
 */

class SourcingRequestsMailer extends Mailer
{

    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendRequestWasAssignedTo($emailRecipient, $data = [])
    {
        $subject = 'Sourcing Request Assigned: '  . $data['sourcingRequest']->customer_product_description  . ' (' . $data['customerName'] . ')';
        $view = 'emails.sourcing-requests.assigned';

        $this->sendTo($emailRecipient, $subject, $view, $data);
    }

    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendRequestsWereImported($emailRecipient, $data = [])
    {
        $subject = 'Sourcing Requests Imported: '  . ' (' . $data['customerName'] . ')';
        $view = 'emails.sourcing-requests.imported';

        $this->sendTo($emailRecipient, $subject, $view, $data);
    }

    /**
     * @param $emailRecipient
     * @param array $data
     */
    public function sendCommentWasCreated($emailRecipient, $data = [])
    {
        $subject = "{$data['commenter']} commented on a sourcing request for :" . $data['sourcingRequest']->customer->name ;
        $view = 'emails.sourcing-requests.comment';

        $this->sendTo($emailRecipient, $subject, $view, $data);
    }

} 