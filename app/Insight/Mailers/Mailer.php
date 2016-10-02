<?php namespace Insight\Mailers;
use Illuminate\Mail\Mailer as Mail;
use Illuminate\Support\Facades\Log;

/**
 * Insight Client Management Portal:
 * Date: 8/16/14
 * Time: 4:14 PM
 */

abstract class Mailer
{
    /**
     * @var Mail
     */
    private $mail;

    /**
     * @param Mail $mail
     */
    public function __construct(Mail $mail)
    {
        $this->mail = $mail;
    }


    /**
     * @param $email
     * @param $subject
     * @param $view
     * @param array $data
     * @param bool $attachment
     * @param bool $fileName
     * @param bool $mime
     */
    public function sendTo($email, $subject, $view, $data = [], $attachment = false, $fileName = false, $mime = false)
    {
        $this->mail->queue($view, $data, function($message) use ($email, $subject, $data, $attachment, $fileName, $mime)
        {
            if ($attachment) {
                $message->attach($attachment, ['as' => $fileName ? : null, 'mime' => $mime ? : null ]);
            }
            $message->to($email)->subject($subject);
        });

        $this->logMailData($email, $subject);
    }

    /**
     * @param $email
     * @param $ccEmail
     * @param $subject
     * @param $view
     * @param array $data
     * @param bool $attachment
     * @param bool $fileName
     * @param bool $mime
     */
    public function sendCc($email, $ccEmail, $subject, $view, $data = [], $attachment = false, $fileName = false, $mime = false)
    {
        $this->mail->queue($view, $data, function($message) use ($email, $ccEmail, $subject, $data, $attachment, $fileName, $mime)
        {
            if ($attachment) {
                $message->attach($attachment, ['as' => $fileName ? : null, 'mime' => $mime ? : null ]);
            }
            $message->to($email);
            $message->cc($ccEmail);
            $message->subject($subject);

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-PreserveRecipients', 'true');

        });

        $this->logMailData($email, $subject);
    }

    /**
     * @param $email
     * @param $fromEmail
     * @param $subject
     * @param $view
     * @param array $data
     * @param bool $attachment
     * @param bool $fileName
     * @param bool $mime
     */
    public function sendFrom($email, $fromEmail, $subject, $view, $data = [], $attachment = false, $fileName = false, $mime = false)
    {
        $this->mail->queue($view, $data, function($message) use ($email, $fromEmail, $subject, $data, $attachment, $fileName, $mime)
        {
            if ($attachment) {
                $message->attach($attachment, ['as' => $fileName ? : null, 'mime' => $mime ? : null ]);
            }
            $message->from($fromEmail);
            $message->to($email);
            $message->subject($subject);

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-PreserveRecipients', 'true');

        });

        $this->logMailData($email, $subject);
    }

    /**
     * @param $email
     * @param $fromEmail
     * @param $subject
     * @param $view
     * @param array $data
     * @param bool $attachment
     * @param bool $fileName
     * @param bool $mime
     */
    public function sendFromAndCcSender($email, $fromEmail, $subject, $view, $data = [], $attachment = false, $fileName = false, $mime = false)
    {
        $this->mail->queue($view, $data, function($message) use ($email, $fromEmail, $subject, $data, $attachment, $fileName, $mime)
        {
            if ($attachment) {
                $message->attach($attachment, ['as' => $fileName ? : null, 'mime' => $mime ? : null ]);
            }
            $message->from($fromEmail);
            $message->to($email);
            $message->cc($fromEmail);
            $message->subject($subject);

            $headers = $message->getHeaders();
            $headers->addTextHeader('X-MC-PreserveRecipients', 'true');

        });

        $this->logMailData($email, $subject);
    }

    /**
     * @param $email
     * @param $subject
     */
    private function logMailData($email, $subject)
    {
        Log::info('------ Send Mail ------');
        Log::info(['email' => $email, 'subject' => $subject]);
        Log::info('------ End Mail ------');
    }
} 