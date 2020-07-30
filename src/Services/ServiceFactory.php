<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

use OZiTAG\Tager\Backend\Sms\Enums\Service;

class ServiceFactory
{
    /**
     * @param $serviceId
     * @param array $serviceParams
     * @return IService|null
     */
    public static function create($serviceId, $serviceParams = [])
    {
        switch ($serviceId) {
            case Service::RocketSms:
                $service = new RocketSmsService();
                break;
            default:
                return null;
        }

        $service->init($serviceParams);

        return $service;
    }
}
