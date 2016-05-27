<?php

namespace Cravelight\Notifications\Email;


interface IEmailHelper
{
    /**
     * @param NotificationEmail $notificationEmail
     * @throws \Exception
     */
    public function sendEmail(NotificationEmail $notificationEmail);
}

