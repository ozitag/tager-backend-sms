<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

use OZiTAG\Tager\Backend\Sms\Enums\SmsLogStatus;
use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsException;
use OZiTAG\Tager\Backend\Sms\Jobs\SendSmsJob;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;

class Executor
{
    public function __construct(protected SmsLogRepository $smsLogRepository, protected TemplateHelper $templateHelper)
    {
    }

    private array|string $recipients = [];

    private string $template;

    private ?array $templateFields = null;

    private string $message;

    private array $options = [];

    public function setTemplate(string $template, ?array $templateFields)
    {
        $this->template = $template;
        $this->templateFields = $templateFields;
        return $this;
    }

    public function setRecipients(array|string $value)
    {
        $this->recipients = $value;
        return $this;
    }

    public function setMessage(string $value)
    {
        $this->message = $value;
        return $this;
    }

    public function setOptions(array $options = []){
        $this->options = $options;
        return $this;
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
        if (!TagerSmsConfig::hasDatabase()) {
            return null;
        }

        return $this->smsLogRepository->reset()->fillAndSave([
            'recipient' => $recipient,
            'body' => $message,
            'status' => $status->value,
            'template_id' => $this->templateHelper->getTemplateDatabaseId($this->template)
        ]);
    }

    private function preparePhoneNumber(string $recipient): string
    {
        $formatter = TagerSmsConfig::getRecipientFormatter();
        if ($formatter) {
            $recipient = $formatter->format($recipient);
        }

        return preg_replace('/[^0-9,.]/', '', $recipient);
    }

    private function send(string $recipient, string $message)
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
            $this->options,
            $log?->id
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
