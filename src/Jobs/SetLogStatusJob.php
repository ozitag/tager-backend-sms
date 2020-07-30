<?php

namespace OZiTAG\Tager\Backend\Sms\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Sms\Enums\LogStatus;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsTemplateRepository;

class SetLogStatusJob extends Job
{
    private $logId;

    private $status;

    private $error;

    public function __construct($logId, $status, $error = null)
    {
        $this->logId = $logId;
        $this->status = $status;
        $this->error = $error;
    }

    public function handle(SmsLogRepository $repository)
    {
        $found = $repository->setById($this->logId);
        if (!$found) {
            return;
        }

        $repository->fillAndSave([
            'status' => $this->status,
            'error' => $this->error
        ]);
    }
}
