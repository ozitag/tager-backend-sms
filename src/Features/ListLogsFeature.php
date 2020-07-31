<?php

namespace OZiTAG\Tager\Backend\Sms\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;

class ListLogsFeature extends Feature
{
    public function handle(MailLogRepository $repository)
    {
        return MailLogResource::collection($repository->all());
    }
}
