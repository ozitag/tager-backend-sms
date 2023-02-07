<?php

namespace OZiTAG\Tager\Backend\Sms\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Sms\Contracts\IService;
use OZiTAG\Tager\Backend\Sms\Enums\SmsLogStatus;
use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsInvalidConfigurationException;
use OZiTAG\Tager\Backend\Sms\Exceptions\TagerSmsServiceException;
use OZiTAG\Tager\Backend\Sms\Services\ServiceFactory;
use OZiTAG\Tager\Backend\Sms\Utils\TagerSmsConfig;

class SendSmsJob extends QueueJob
{
    public function __construct(protected $recipient,protected  $message, protected $options, protected $logId)
    {
    }

    private function setLogStatus(SmsLogStatus $status, $response = null, $error = null)
    {
        dispatch(new SetLogStatusJob($this->logId, $status, $response, $error));
    }

    /** @var IService */
    private $_service = false;

    /**
     * @return IService
     * @throws \Exception
     */
    private function service()
    {
        if ($this->_service !== false) {
            return $this->_service;
        }

        $serviceId = TagerSmsConfig::getServiceId();
        if (empty($serviceId)) {
            throw new TagerSmsInvalidConfigurationException('Sender Service is not set');
        }

        $serviceParams = TagerSmsConfig::getServiceParams();

        $service = ServiceFactory::create($serviceId, $serviceParams);
        if (!$service) {
            throw new TagerSmsInvalidConfigurationException('Cannot create service with id ' . $serviceId);
        }

        $this->_service = $service;

        return $service;
    }

    /**
     * @return bool
     */
    private function isRecipientAllowed()
    {
        $validPhones = TagerSmsConfig::getAllowedPhones();
        return $validPhones == '*' || in_array($this->recipient, $validPhones);
    }

    public function handle()
    {
        if (!$this->isRecipientAllowed()) {
            $this->setLogStatus(SmsLogStatus::Skip);
            return;
        }

        $this->setLogStatus(SmsLogStatus::Sending);

        try {
            $this->service()->send($this->recipient, $this->message, $this->options);
            $this->setLogStatus(SmsLogStatus::Success, $this->service()->getResponse());
        } catch (TagerSmsServiceException $exception) {
            $this->setLogStatus(SmsLogStatus::Failure, null, 'Service Error: ' . $exception->getMessage());
        } catch (\Exception $exception) {
            $this->setLogStatus(SmsLogStatus::Failure, null, $exception->getMessage());
        }
    }
}
