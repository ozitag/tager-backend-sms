<?php

namespace OZiTAG\Tager\Backend\Sms\Models;

use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class TagerSmsLog
 * @package OZiTAG\Tager\Backend\Sms\Models
 *
 * @property integer $template_id
 * @property string $recipient
 * @property string $body
 * @property string $status
 * @property string $error
 * @property string $service_response
 *
 * @property TagerSmsTemplate $template
 */
class TagerSmsLog extends TModel
{
    protected $table = 'tager_sms_logs';

    static string $defaultOrder = 'created_at desc';

    protected $fillable = [
        'template_id',
        'recipient',
        'body',
        'status',
        'error',
        'service_response',
    ];

    public function template()
    {
        return $this->belongsTo(TagerSmsTemplate::class);
    }
}
