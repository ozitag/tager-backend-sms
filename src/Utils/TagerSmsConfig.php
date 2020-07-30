<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

class TagerSmsConfig
{
    public static function getTemplate($template)
    {
        $config = config('tager-sms.templates');

        $template = $config[$template] ?? null;
        if (!$template) {
            return [];
        }

        return $template;
    }

    /**
     * @param string $template
     * @return array
     */
    public function getTemplateVariables($template)
    {
        $template = $this->getTemplate($template);

        $params = $template['templateParams'] ?? [];

        $result = [];
        foreach ($params as $name => $label) {
            $result[] = [
                'key' => $name,
                'label' => $label
            ];
        }

        return $result;
    }

    /**
     * @return boolean
     */
    public static function isDebug()
    {
        return (boolean)config('tager-sms.debug', false);
    }

    /**
     * @return bool
     */
    public static function hasDatabase()
    {
        return !(boolean)config('tager-sms.no_database', false);
    }

    /**
     * @return string
     */
    public static function getTextTemplate()
    {
        return (string)config('tager-sms.text_template', '{text}');
    }

    /**
     * @return array
     */
    public static function getAllowedPhones()
    {
        $value = config('tager-sms.allow_phones');
        if (!$value) {
            return [];
        }

        return is_array($value) ? $value : [(string)$value];
    }

    public static function getServiceId()
    {
        return config('tager-sms.service.id', null);
    }

    public static function getServiceParams()
    {
        $value = config('tager-sms.service.params', []);
        if (!is_array($value)) {
            throw new \Exception('Invalid service params - must be an array');
        }
        return $value;
    }
}
