<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

use OZiTAG\Tager\Backend\Sms\Services\ServiceFactory;

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

    /**
     * @return string
     */
    private function getRawMessage()
    {
        if (!empty($this->template)) {
            $templateHelper = new TemplateHelper();
            $result = $templateHelper->getRawText($this->template, $this->templateFields);
        } else {
            $result = $this->message;
        }

        if (!$result) {
            return null;
        }

        $baseTemplate = TagerSmsConfig::getTextTemplate();
        if (empty($baseTemplate)) {
            return $result;
        }

        return str_replace('{text}', $result, $baseTemplate);
    }

    /**
     * @param $recipient
     * @return bool
     */
    private function isRecipientAllowed($recipient)
    {
        $validPhones = TagerSmsConfig::getAllowedPhones();
        return empty($validPhones) || in_array($recipient, $validPhones);
    }

    private function onRecipientDisallow($recipient)
    {

    }

    /**
     * @return array
     */
    private function getRecipients()
    {
        if (empty($this->recipients)) {
            return [];
        }

        $all = is_array($this->recipients) ? $this->recipients : [$this->recipients];

        $result = [];
        foreach ($all as $recipient) {
            if ($this->isRecipientAllowed($recipient)) {
                $result[] = $recipient;
            } else {
                $this->onRecipientDisallow($recipient);
            }
        }

        return $result;
    }

    private function sendInDebugMode($recipients, $message)
    {

    }

    private function send($recipients, $message)
    {
        $serviceId = TagerSmsConfig::getServiceId();
        if (empty($serviceId)) {
            throw new \Exception('Service is not set');
        }

        $service = ServiceFactory::create($serviceId, TagerSmsConfig::getServiceParams());

        if (count($recipients) == 1) {
            $service->sendSingle($recipients[0], $message);
        } else {
            $service->sendBatch($recipients, $message);
        }
    }

    public function execute()
    {
        $message = $this->getRawMessage();
        if (!$message) {
            throw new \Exception('Message is empty');
        }

        $recipients = $this->getRecipients();
        if (empty($recipients)) {
            throw new \Exception('Recipient list is empty');
        }

        if (TagerSmsConfig::isDebug()) {
            $this->sendInDebugMode($recipients, $message);
        } else {
            $this->send($recipients, $message);
        }
    }
}
