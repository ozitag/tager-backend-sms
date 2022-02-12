<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

use OZiTAG\Tager\Backend\Sms\Enums\SmsLogStatus;
use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsException;
use OZiTAG\Tager\Backend\Sms\Jobs\SendSmsJob;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;

class Executor
{
    /** @var SmsLogRepository */
    private $smsLogRepository;

    /** @var TemplateHelper */
    private $templateHelper;

    public function __construct(SmsLogRepository $smsLogRepository, TemplateHelper $templateHelper)
    {
        $this->smsLogRepository = $smsLogRepository;
        $this->templateHelper = $templateHelper;
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
            $result = $this->templateHelper->getRawText($this->template, $this->templateFields);
        } else {
            $result = $this->message;
        }

        if (!$result) {
            return null;
        }

        $baseTemplate = TagerSmsConfig::getMessageTemplate();
        if (empty($baseTemplate)) {
            return $result;
        }

        return str_replace('{text}', $result, $baseTemplate);
    }

    /**
     * @return array
     */
    private function getRecipients()
    {
        if (empty($this->recipients)) {
            if (!empty($this->template)) {
                return $this->templateHelper->getTemplateRecipients($this->template);
            }
            return [];
        }

        return is_array($this->recipients) ? $this->recipients : [$this->recipients];
    }

    private function createLogItem($recipient, $message, SmsLogStatus $status = SmsLogStatus::Created)
    {
        if (TagerSmsConfig::hasDatabase() == false) {
            return null;
        }

        $this->smsLogRepository->reset();
        return $this->smsLogRepository->fillAndSave([
            'recipient' => $recipient,
            'body' => $message,
            'status' => $status->value,
            'template_id' => $this->templateHelper->getTemplateDatabaseId($this->template)
        ]);
    }

    private function preparePhoneNumber($recipient)
    {
        $formatter = TagerSmsConfig::getRecipientFormatter();
        if ($formatter) {
            $recipient = $formatter->format($recipient);
        }

        return preg_replace('/[^0-9,.]/', '', $recipient);
    }

    /**
     * @param string $recipient
     * @param string $message
     * @throws \Exception
     */
    private function send($recipient, $message)
    {
        $recipient = $this->preparePhoneNumber($recipient);

        if (TagerSmsConfig::isDisabled()) {
            $this->createLogItem($recipient, $message, SmsLogStatus::Disabled);
            return;
        }

        $log = $this->createLogItem($recipient, $message, SmsLogStatus::Created);

        dispatch(new SendSmsJob(
            $recipient,
            $message,
            $log ? $log->id : null
        ));
    }

    public function execute()
    {
        $message = $this->getRawMessage();
        if (!$message) {
            throw new TagerSmsException('Message is empty');
        }

        $recipients = $this->getRecipients();

        foreach ($recipients as $recipient) {
            $this->send($recipient, $message);
        }
    }
}
