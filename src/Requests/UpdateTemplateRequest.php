<?php

namespace OZiTAG\Tager\Backend\Sms\Requests;

use OZiTAG\Tager\Backend\Crud\Requests\CrudFormRequest;

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
