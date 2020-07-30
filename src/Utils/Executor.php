<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

use OZiTAG\Tager\Backend\Sms\Enums\LogStatus;
use OZiTAG\Tager\Backend\Sms\Jobs\SendSmsInDebugModeJob;
use OZiTAG\Tager\Backend\Sms\Jobs\SendSmsJob;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsTemplateRepository;
use OZiTAG\Tager\Backend\Sms\Services\ServiceFactory;

class Executor
{
    private $smsLogRepository;

    public function __construct(SmsLogRepository $smsLogRepository)
    {
        $this->smsLogRepository = $smsLogRepository;
    }

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

    private function createLogItem($recipient, $message, $isDebug = false)
    {
        return $this->smsLogRepository->fillAndSave([
            'recipient' => $recipient,
            'body' => $message,
            'status' => LogStatus::Created,
            'debug' => $isDebug
        ]);
    }

    private function sendInDebugMode($recipient, $message)
    {
        $log = $this->createLogItem($recipient, $message, true);

        dispatch(new SendSmsInDebugModeJob(
            $log->id
        ));
    }

    /**
     * @param string $recipient
     * @param string $message
     * @throws \Exception
     */
    private function send($recipient, $message)
    {
        $log = $this->createLogItem($recipient, $message, false);

        dispatch(new SendSmsJob(
            $recipient,
            $message,
            $log->id
        ));
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

        foreach ($recipients as $recipient) {
            if (TagerSmsConfig::isDebug()) {
                $this->sendInDebugMode($recipient, $message);
            } else {
                $this->send($recipient, $message);
            }
        }
    }
}
