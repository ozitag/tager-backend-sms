<?php

namespace OZiTAG\Tager\Backend\Sms\Requests;

use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;

/**
 * Class UpdateTemplateRequest
 * @package OZiTAG\Tager\Backend\Sms\Requests
 *
 * @property string $body
 * @property string[] $recipients
 */
class UpdateTemplateRequest extends CrudFormRequest
{
    public function rules()
    {
        return [
            'body' => 'required|string',
            'recipients' => 'nullable|array',
            'recipients.*' => 'string'
        ];
    }
}
