<?php

namespace OZiTAG\Tager\Backend\Sms\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use OZiTAG\Tager\Backend\Core\Models\TModel;

/**
 * Class TagerSmsTemplate
 * @package OZiTAG\Tager\Backend\Sms\Models
 *
 * @property string $name
 * @property string $body
 * @property string $recipients
 * @property bool $changed_by_admin
 */
class TagerSmsTemplate extends TModel
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
        'body',
        'recipients',
        'changed_by_admin'
    ];
}
