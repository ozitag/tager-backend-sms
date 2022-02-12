<?php

namespace OZiTAG\Tager\Backend\Sms\Enums;

enum SmsLogStatus:string
{
    case Disabled = 'DISABLED';
    case Created = 'CREATED';
    case Sending = 'SENDING';
    case Skip = 'SKIP';
    case Failure = 'FAILURE';
    case Success = 'SUCCESS';
}
