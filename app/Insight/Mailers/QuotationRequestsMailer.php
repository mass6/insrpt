<?php namespace Insight\Mailers;

use Insight\Quotations\QuotationRequest;

/**
 * Class QuotationRequestsMailer
 * @package Insight\Mailers
 */
class QuotationRequestsMailer
{

    /**
     * @var MandrillMailer
     */
    private $mailer;


    public function __construct(MandrillMailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param $emailRecipient
     * @param $fromEmail
     * @param $ccSender
     * @param array $data
     * @param $spreadsheet
     */
    public function sendQuotationRequestWasSent($emailRecipient, $fromEmail, $ccSender = null, $data = [], $spreadsheet)
    {
        $subject = 'Quotation Request ' . $data['quotationRequest']['quotation_request_id'] . ' from ' . $data['company'];
        $view = 'emails.quotation-requests.sent';
        $file = $spreadsheet['full'];

        $response = $this->mailer->sendFrom($emailRecipient, $fromEmail, $subject, $view, $data, $file, $data['quotationRequest']['quotation_request_id'] . '.' . $spreadsheet['ext'], $ccSender);

        $this->updateDeliveryStatus($emailRecipient, $data, $response);

    }

    /**
     * @param $emailRecipient
     * @param $data
     * @param $response
     */
    protected function updateDeliveryStatus($emailRecipient, $data, $response)
    {
        if ($response) {
            $quotationRequest = QuotationRequest::findOrFail($data['quotationRequest']['id']);
            $quotationRequest->update([
                'delivery_email'        => $emailRecipient,
                'email_delivery_status' => 'Sent'
            ]);
        }
    }

}