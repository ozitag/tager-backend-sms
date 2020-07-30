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

    public function sendRaw($recipients, $text)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setMessage($text);
        $this->executor->execute();
    }

    public function sendUsingTemplate($recipients, $template, $templateFields = null)
    {
        $this->executor->setRecipients($recipients);
        $this->executor->setTemplate($template, $templateFields);
        $this->executor->execute();
    }
}
