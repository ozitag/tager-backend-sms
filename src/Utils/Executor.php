<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

class Executor
{
    private $recipients = [];

    private $template = null;

    private $templateFields = null;

    private $message = null;

    public function setTemplate($template, $templateFields)
    {
        $this->template = $template;
        $this->templateFields = $templateFields;
    }

    public function setRecipients($value)
    {
        $this->recipients = $value;
    }

    public function setMessage($value)
    {
        $this->message = $value;
    }

    private function getRawMessage()
    {
        if (!empty($this->template)) {
            $templateHelper = new TemplateHelper();
            $result = $templateHelper->getRawText($this->template, $this->templateFields);
        } else {
            $result = $this->message;
        }

        $baseTemplate = TagerSmsConfig::getTextTemplate();
        if (empty($baseTemplate)) {
            return $result;
        }

        return str_replace('{text}', $result, $baseTemplate);
    }

    private function sendInDebugMode($recipients, $message)
    {

    }

    private function getSender()
    {

    }

    public function execute()
    {
        $message = $this->getRawMessage();

        if (TagerSmsConfig::isDebug()) {
            $this->sendInDebugMode($this->recipients, $message);
        }
    }
}
