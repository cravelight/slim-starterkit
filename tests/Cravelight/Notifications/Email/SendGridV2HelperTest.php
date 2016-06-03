<?php

use Cravelight\Notifications\Email\SendGridV2Helper;
use Cravelight\Notifications\Email\NotificationEmail;


class SendGridV2HelperTest extends PHPUnit_Framework_TestCase
{

    protected function setUp()
    {

    }


    public function testCreateObject()
    {
        // Arrange|Given

        // Act|When
        $helper = $this->getHelper();

        // Assert|Then
        $this->assertInstanceOf('Cravelight\Notifications\Email\SendGridV2Helper', $helper);
    }


    public function testSendHtmlEmailSuccess()
    {
        // Arrange|Given
        $email = new NotificationEmail();
        $email->toAddress = !empty(getenv('SENDGRID_FROM_ADDRESS')) ? getenv('SENDGRID_FROM_ADDRESS') : 'no-reply@example.com';
        $email->toName = 'Mr Tester';
        $email->subject = 'Test email from class SendGridV2HelperTest->testSendHtmlEmailSuccess';
        $email->bodyContent = '<p>This is a test <strong>HTML</strong> email which should contain a link to <a href="http://www.google.com">Google</a>.</p>';
        $email->bodyIsHtml = true;
        $helper = $this->getHelper();
        $sendException = null;

        // Act|When
        try {
            $helper->sendEmail($email);
        } catch (Exception $e) {
            $sendException = $e->getMessage();
        }

        // Assert|Then
        $this->assertNull($sendException, $sendException);
        $this->assertInstanceOf('Cravelight\Notifications\Email\SendGridV2Helper', $helper);
    }



    private function getHelper() : SendGridV2Helper
    {
        $apikey = !empty(getenv('SENDGRID_API_KEY')) ? getenv('SENDGRID_API_KEY') : 'this.should.be.the.sendgrid.api.key.but.its.not';
        $defaultFromAddress = !empty(getenv('SENDGRID_FROM_ADDRESS')) ? getenv('SENDGRID_FROM_ADDRESS') : 'no-reply@example.com';
        return new SendGridV2Helper($apikey, $defaultFromAddress);
    }



}
