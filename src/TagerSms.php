<?php

namespace OZiTAG\Tager\Backend\Sms;

use OZiTAG\Tager\Backend\Sms\Utils\Executor;

class TagerSms
{
    private $executor;

    public function __construct(Executor $executor)
    {
        $this->executor = $executor;
    }

    /**
     * @param string[] $recipients
     * @param string $text
     * @throws Exceptions\TagerSmsException
     */
    public function sendRaw($recipients, $text)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setMessage($text);
        $this->executor->execute();
    }

    /**
     * @param string[] $recipients
     * @param string $template
     * @param string[]|null $templateFields
     * @throws Exceptions\TagerSmsException
     */
    public function sendUsingTemplate($recipients, $template, $templateFields = null)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setTemplate($template, $templateFields);
        $this->executor->execute();
    }
}
