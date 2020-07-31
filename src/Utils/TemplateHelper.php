<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

use OZiTAG\Tager\Backend\Sms\Repositories\SmsTemplateRepository;

class TemplateHelper
{
    private $templateRepository;

    public function __construct(SmsTemplateRepository $templateRepository)
    {
        $this->templateRepository = $templateRepository;
    }


    private function getTemplateText($templateId)
    {
        if (TagerSmsConfig::hasDatabase()) {
            $templateDatabase = $this->templateRepository->findByTemplate($templateId);
            if ($templateDatabase && $templateDatabase->changed_by_admin) {
                return $templateDatabase->body;
            }
        }

        $configTemplate = TagerSmsConfig::getTemplate($templateId);

        return $configTemplate && isset($configTemplate['value']) ? $configTemplate['value'] : null;
    }

    public function getTemplateDatabaseId($template)
    {
        if (TagerSmsConfig::hasDatabase()) {
            $templateDatabase = $this->templateRepository->findByTemplate($template);
            if ($templateDatabase) {
                return $templateDatabase->id;
            }
        }

        return null;
    }


    /**
     * @param $template
     * @param array $templateFields
     * @return string|null
     */
    public function getRawText($template, $templateFields = [])
    {
        $text = $this->getTemplateText($template);

        if (!$text) {
            return null;
        }

        foreach ($templateFields as $field => $value) {
            $text = str_replace('{' . $field . '}', $value, $text);
        }

        return $text;
    }

    public function getTemplateRecipients($template)
    {
        if (TagerSmsConfig::hasDatabase()) {
            $templateDatabase = $this->templateRepository->findByTemplate($template);
            if ($templateDatabase && $templateDatabase->changed_by_admin) {
                return $templateDatabase->recipients ? explode(',', $templateDatabase->recipients) : [];
            }
        }

        $configTemplate = TagerSmsConfig::getTemplate($template);

        $recipients = isset($configTemplate['recipients']) ? $configTemplate['recipients'] : [];
        if (is_string($recipients)) {
            $recipients = explode(',', $recipients);
        }

        return $recipients;
    }
}
