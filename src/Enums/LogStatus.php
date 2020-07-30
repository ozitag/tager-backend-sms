<?php

namespace OZiTAG\Tager\Backend\Sms\Enums;

use OZiTAG\Tager\Backend\Core\Enums\Enum;

class LogStatus extends Enum
{
    const Created = 'CREATED';
    const Sending = 'SENDING';
    const Skip = 'SKIP';
    const Failure = 'FAILURE';
    const Success = 'SUCCESS';
}
