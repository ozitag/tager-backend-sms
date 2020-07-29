<?php

namespace OZiTAG\Tager\Backend\Sms\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TagerSmsTemplate extends Model
{
    use SoftDeletes;

    protected $table = 'tager_sms_templates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'value',
        'recipients'
    ];
}
