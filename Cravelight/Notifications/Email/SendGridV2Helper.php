<?php

namespace Cravelight\Notifications\Email;


use SendGrid;
use SendGrid\Email;


class SendGridV2Helper implements IEmailHelper
{
    /**
     * @var SendGrid
     */
    protected $sendgrid;

    /**
     * @var string
     */
    protected $defaultFromAddress;


    /**
     * SendGridV2Helper constructor.
     * @param string $sendGridApiKey
     * @param string $defaultFromAddress
     */
    public function __construct(string $sendGridApiKey, string $defaultFromAddress)
    {
        $this->sendgrid = new SendGrid($sendGridApiKey);
        $this->defaultFromAddress = $defaultFromAddress;
    }


    /**
     * @param NotificationEmail $notificationEmail
     * @throws \Exception
     */
    public function sendEmail(NotificationEmail $notificationEmail)
    {
        $email = $this->packageNotificationEmail($notificationEmail);
        try {
            $this->sendgrid->send($email);
        } catch(\SendGrid\Exception $e) {
            throw new \Exception($e->getMessage(), $e->getCode());
        }
    }


    private function packageNotificationEmail(NotificationEmail $nEmail) : Email
    {
        $email = new Email();

        // To address and name
        $email->addTo($nEmail->toAddress);
        if (!empty($nEmail->toName)) {
            $email->addToName($nEmail->toName);
        }

        // subject
        $email->setSubject($nEmail->subject);

        // body
        if ($nEmail->bodyIsHtml) {
            $email->setHtml($nEmail->bodyContent);
        } else {
            $email->setText($nEmail->bodyContent);
        }

        // from, reply and bcc addresses
        $email->from = empty($nEmail->fromAddressOverride) ? $this->defaultFromAddress : $nEmail->fromAddressOverride;
        $email->replyTo = empty($nEmail->replyAddressOverride) ? $this->defaultFromAddress : $nEmail->replyAddressOverride;
        foreach ($nEmail->bccAddresses as $bccAddress) {
            $email->addBcc($bccAddress);
        }

        return $email;
    }
}


