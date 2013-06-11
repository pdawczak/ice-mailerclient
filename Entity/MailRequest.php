<?php

namespace Ice\MailerClientBundle\Entity;

class MailRequest
{
    /**
     * @var array|string
     */
    private $to;

    /**
     * @var string
     */
    private $templateName;

    /**
     * @param mixed $templateName
     * @return MailRequest
     */
    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTemplateName()
    {
        return $this->templateName;
    }

    /**
     * Takes an array of Ice IDs, or a single Ice ID
     *
     * @param mixed $to
     * @return MailRequest
     */
    public function setTo($to)
    {
        $this->to = $to;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTo()
    {
        return $this->to;
    }
}