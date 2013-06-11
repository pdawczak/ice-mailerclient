<?php
namespace Ice\MailerClientBundle\Service;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Service\Client;
use Guzzle\Service\Command\DefaultRequestSerializer;
use Ice\MailerClientBundle\Entity\MailRequest;
use Guzzle\Service\Command\OperationCommand;

class MailerClient
{
    /**
     * @var MailerRestClient
     */
    private $restClient;

    /**
     * @param \Ice\MailerClientBundle\Service\MailerRestClient $restClient
     * @return MailerClient
     */
    public function setRestClient($restClient)
    {
        $this->restClient = $restClient;
        return $this;
    }

    /**
     * @return \Ice\MailerClientBundle\Service\MailerRestClient
     */
    public function getRestClient()
    {
        return $this->restClient;
    }

    /**
     * Send a mail to the specified ICE id(s) with the specified template. We don't care about the response in this
     * version.
     *
     * @param array|string $to
     * @param string $templateName
     */
    public function postMail($to, $templateName)
    {
        $this->getRestClient()->getCommand('PostMail', array(
            'mailRequest'=>(new MailRequest())->setTemplateName($templateName)->setTo($to)
        ))->execute();
    }
}