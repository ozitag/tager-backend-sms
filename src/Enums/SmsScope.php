<?php

namespace OZiTAG\Tager\Backend\Sms\Enums;

use OZiTAG\Tager\Backend\Core\Enums\Enum;

class SmsScope extends Enum
{
    const ViewTemplates = 'sms.view-templates';
    const EditTemplates = 'sms.edit-templates';
    const ViewLogs = 'sms.edit-logs';
}
