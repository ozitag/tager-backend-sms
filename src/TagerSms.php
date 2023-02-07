<?php

namespace OZiTAG\Tager\Backend\Sms;

use OZiTAG\Tager\Backend\Sms\Utils\Executor;

class TagerSms
{
    public function __construct(protected Executor $executor)
    {
    }

    public function sendRaw(array|string $recipients, string $text, array $options = [])
    {
        $this->executor->setRecipients($recipients)->setMessage($text)->setOptions($options)->execute();
    }

    public function sendUsingTemplate(array|string $recipients, string $template, ?array $templateFields = null, array $options = [])
    {
        $this->executor->setRecipients($recipients)->setTemplate($template, $templateFields)->setOptions($options)->execute();
    }
}
