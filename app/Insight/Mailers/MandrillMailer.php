<?php

namespace Insight\Mailers;

use Illuminate\Mail\Message;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;

/**
 * Class MandrillMailer
 * @package Insight\Mailers
 */
class MandrillMailer
{

    /**
     * @var \Mandrill
     */
    private $mandrill;

    public function __construct()
    {
        $mandrill       = new \Mandrill(Config::get('services.mandrill.secret'));
        $this->mandrill = $mandrill;
    }

    public function getClient()
    {
        return $this->mandrill;
    }
    public function users()
    {
        return $this->mandrill->users->info();
    }

    public function sendFrom($email, $fromEmail, $subject, $view, $data = [], $attachment = false, $fileName = false, $ccSender = false)
    {
        $html = View::make($view)->with($data)->render();
        $message = [
            'html' => $html,
            'subject' => $subject,
            'from_email' => $fromEmail,
            'aysnc' => false,
            'to' => [
                [
                    'email' => $email,
                    'type' => 'to'
                ],
            ],
            'track_opens' => true,
            'track_clicks' => true,
            'auto_text' => true,
            'tags' => array('quotation-requests'),
            'metadata' => array('quotation-request' => $data['quotationRequest']['quotation_request_id']),
        ];
        $message = $this->addCc($fromEmail, $ccSender, $message);
        $message = $this->addAttachment($attachment, $fileName, $message);

        $response = $this->mandrill->messages->send($message);
        $this->logMailData($email, $subject, $response);

        return $response;
    }

    /**
     * @param $fromEmail
     * @param $ccSender
     * @param $message
     *
     * @return mixed
     */
    protected function addCc($fromEmail, $ccSender, $message)
    {
        if ($ccSender) {
            $message['to'][] = [
                'email' => $fromEmail,
                'type'  => 'cc'
            ];

            return $message;
        }

        return $message;
    }


    /**
     * @param $attachment
     * @param $fileName
     * @param $message
     *
     * @return mixed
     */
    protected function addAttachment($attachment, $fileName, $message)
    {
        if ($attachment) {
            $finfo    = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $attachment);
            finfo_close($finfo);
            $file               = file_get_contents($attachment);
            $attachment_encoded = base64_encode($file);

            $message['attachments'][] = [
                'type'    => $mimeType,
                'name'    => $fileName,
                'content' => $attachment_encoded
            ];

            return $message;
        }

        return $message;
    }


    /**
     * @param $email
     * @param $subject
     * @param $response
     */
    private function logMailData($email, $subject, $response)
    {
        Log::info([
            '------ Send Mandrill Mail ------',
            ['email' => $email, 'subject' => $subject],
            ['Mandrill Mail Response' => $response],
        ]);
    }

}
