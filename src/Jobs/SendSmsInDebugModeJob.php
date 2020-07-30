<?php

namespace OZiTAG\Tager\Backend\Sms\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Sms\Enums\LogStatus;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;

class SendSmsInDebugModeJob extends QueueJob
{
    private $logId;

    public function __construct($logId)
    {
        $this->logId = $logId;
    }

    public function handle()
    {
        dispatch(new SetLogStatusJob($this->logId, LogStatus::Success));
    }
}
