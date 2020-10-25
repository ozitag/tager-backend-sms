<?php

namespace OZiTAG\Tager\Backend\Sms\Models;

use Illuminate\Database\Eloquent\Model;

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
class TagerSmsLog extends Model
{
    protected $table = 'tager_sms_logs';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'template_id',
        'recipient',
        'body',
        'status',
        'error',
        'service_response',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function template()
    {
        return $this->belongsTo(TagerSmsTemplate::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('created_at', 'desc');
        });
    }
}
