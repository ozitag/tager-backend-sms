<?php

namespace OZiTAG\Tager\Backend\Sms\Enums;

enum SmsScope:string
{
    case ViewTemplates = 'sms.view-templates';
    case EditTemplates = 'sms.edit-templates';
    case ViewLogs = 'sms.edit-logs';
}
