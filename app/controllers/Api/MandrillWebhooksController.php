<?php namespace Api;

use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;
use Insight\Quotations\QuotationRequest;

class MandrillWebhooksController extends \Controller {


	public function webhook()
	{

        if (! $this->authenticateRequest()) {
            Log::error('Unknown webhook signature');
            exit();
        }

        $messages = Input::get('mandrill_events');
        $eventMessages = json_decode($messages);

        $this->updateQuotationRequestMailDeliveryStatus($eventMessages);
    }

    protected function authenticateRequest()
    {
        return $this->generateSignature() === Request::header('X-Mandrill-Signature');
    }

    /**
     * Generates a base64-encoded signature for a Mandrill webhook request.
     *
     * @return string
     */
    function generateSignature() {
        $webhook_key = getenv('MANDRILL_WEBHOOK_SIGNATURE');
        $url = getenv('MANDRILL_WEBHOOK_URL');
        $params = Input::all();
        $signed_data = $url;
        ksort($params);
        foreach ($params as $key => $value) {
            $signed_data .= $key;
            $signed_data .= $value;
        }

        return base64_encode(hash_hmac('sha1', $signed_data, $webhook_key, true));
    }

    /**
     * @param $eventMessages
     */
    protected function updateQuotationRequestMailDeliveryStatus($eventMessages)
    {
        foreach ($eventMessages as $message) {
            $eventName = QuotationRequest::getEmailDeliveryStatus($message->event);
            if (isset($message->msg->metadata->{'quotation-request'}) && $eventName) {
                $quotationRequest = QuotationRequest::where('quotation_request_id', $message->msg->metadata->{'quotation-request'})->first();

                if ($message->msg->email == $quotationRequest->delivery_email) {
                    $quotationRequest->update([ 'email_delivery_status' => $eventName ]);

                    Log::info('Quotation Request # ' . $quotationRequest->quotation_request_id . ' email delivery status updated to ' .  $eventName
                        . ' in response to ' . $message->event . ' event received from ' . $message->msg->email );
                }
            }
        }
    }

}
