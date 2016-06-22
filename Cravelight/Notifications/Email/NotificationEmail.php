<?php

namespace Cravelight\Notifications\Email;


class NotificationEmail
{
    /**
     * @var string (required) Must be a valid email address
     */
    public $toAddress;

    /**
     * @var string (optional)
     */
    public $toName;

    /**
     * @var string (required)
     */
    public $subject;

    /**
     * @var string (optional)
     */
    public $bodyContent;

    /**
     * @var bool (optional)
     */
    public $bodyIsHtml = false;

    /**
     * @var array (optional) Each item must be a valid email addresse
     */
    public $bccAddresses = [];

    /**
     * @var string (optional) Must be a valid email address. Used to override the default from address on a given notification
     */
    public $fromAddressOverride;

    /**
     * @var string (optional) Must be a valid email address. Used to override the default reply-to address on a given notification
     */
    public $replyAddressOverride;

}
