<?php

namespace OZiTAG\Tager\Backend\Sms\Models;

use Illuminate\Database\Eloquent\Model;

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
