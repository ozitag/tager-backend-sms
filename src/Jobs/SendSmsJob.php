<?php

namespace OZiTAG\Tager\Backend\Sms\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\QueueJob;
use OZiTAG\Tager\Backend\Sms\Enums\LogStatus;

class SendSmsJob extends QueueJob
{
    private $recipient;

    private $message;

    private $logId;

    public function __construct($recipient, $message, $logId)
    {
        $this->recipient = $recipient;
        $this->message = $message;
        $this->logId = $logId;
    }

    private function setLogStatus($status, $error = null)
    {
        dispatch(new SetLogStatusJob($this->logId, $status, $error));
    }

    private function execute()
    {

    }

    public function handle()
    {
        $this->setLogStatus(LogStatus::Sending);

        try {
            $this->execute();
            $this->setLogStatus(LogStatus::Success);
        } catch (\Exception $exception) {
            $this->setLogStatus(LogStatus::Failure, $exception->getMessage());
        }
    }
}
