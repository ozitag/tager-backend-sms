<?php

namespace OZiTAG\Tager\Backend\Sms\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;

class SetLogStatusJob extends Job
{
    private $logId;

    private $status;

    private $error;

    private $response;

    public function __construct($logId, $status, $response, $error = null)
    {
        $this->logId = $logId;
        $this->status = $status;
        $this->error = $error;
        $this->response = $response;
    }

    public function handle(SmsLogRepository $repository)
    {
        if (!$this->logId) {
            return;
        }

        $found = $repository->setById($this->logId);
        if (!$found) {
            return;
        }

        $repository->fillAndSave([
            'status' => $this->status,
            'error' => $this->error,
            'service_response' => $this->response
        ]);
    }
}
