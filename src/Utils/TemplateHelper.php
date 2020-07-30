<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

class TemplateHelper
{
    private function getTemplateText($templateId)
    {
        $configTemplate = TagerSmsConfig::getTemplate($templateId);
        if (!$configTemplate) {
            return null;
        }

        return isset($configTemplate['text']) ? $configTemplate['text'] : null;
    }

    /**
     * @param $templateId
     * @param array $templateFields
     * @return string|string[]
     */
    public function getRawText($templateId, $templateFields = [])
    {
        $text = $this->getTemplateText($templateId);
        if (!$text) {
            return null;
        }

        foreach ($templateFields as $field => $value) {
            $text = str_replace('{' . $field . '}', $value);
        }

        return $text;
    }
}
