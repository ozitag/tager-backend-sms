<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

use OZiTAG\Tager\Backend\Sms\Contracts\IService;
use OZiTAG\Tager\Backend\Sms\Enums\SmsService;

class ServiceFactory
{
    public static function create(string $serviceId, array $serviceParams = []): ?IService
    {
        switch ($serviceId) {
            case SmsService::RocketSms->value:
                $service = new RocketSmsService();
                break;
            default:
                return null;
        }

        $service->init($serviceParams);

        return $service;
    }
}
