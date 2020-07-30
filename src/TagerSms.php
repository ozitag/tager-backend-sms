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
        $sms = new Executor();
        $sms->setRecipients($recipients);
        $sms->setMessage($text);
        $sms->execute();
    }

    public function sendUsingTemplate($recipients, $template, $templateFields = null)
    {
        $sms = new Executor();
        $sms->setRecipients($recipients);
        $sms->setTemplate($template, $templateFields);
        $sms->execute();
    }
}
