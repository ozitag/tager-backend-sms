<?php

namespace OZiTAG\Tager\Backend\Sms\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Sms\Models\TagerSmsLog;

class SmsLogRepository extends EloquentRepository
{
    public function __construct(TagerSmsLog $model)
    {
        parent::__construct($model);
    }
}
