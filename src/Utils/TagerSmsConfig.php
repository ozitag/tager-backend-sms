<?php

namespace OZiTAG\Tager\Backend\Sms\Utils;

use OZiTAG\Tager\Backend\Sms\Contracts\IRecipientFormatter;
use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsInvalidConfigurationException;

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
     * @return bool
     */
    public static function hasDatabase()
    {
        return !(boolean)config('tager-sms.no_database', false);
    }

    /**
     * @return string
     */
    public static function getMessageTemplate()
    {
        return (string)config('tager-sms.message_template', '{text}');
    }

    /**
     * @return array|string
     */
    public static function getAllowedPhones()
    {
        $value = config('tager-sms.allow_phones');
        if (is_null($value)) {
            return '*';
        }

        if (!$value) {
            return [];
        }

        return is_array($value) ? $value : explode(',', $value);
    }

    public static function getServiceId()
    {
        return config('tager-sms.service.id', null);
    }

    public static function getServiceParams()
    {
        $value = config('tager-sms.service.params', []);
        if (!is_array($value)) {
            throw new TagerSmsInvalidConfigurationException('Invalid service params - must be an array');
        }
        return $value;
    }

    /**
     * @return IRecipientFormatter
     * @throws TagerSmsInvalidConfigurationException
     */
    public static function getRecipientFormatter()
    {
        $value = config('tager-sms.recipient_formatter');
        if (!$value) {
            return null;
        }

        if (class_implements($value, IRecipientFormatter::class) == false) {
            throw new TagerSmsInvalidConfigurationException('Formatter should implements IRecipientFormatter contract');
        }

        return new $value;
    }
}
