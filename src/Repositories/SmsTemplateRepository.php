<?php

namespace OZiTAG\Tager\Backend\Sms\Repositories;

use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Sms\Models\TagerSmsTemplate;

class SmsTemplateRepository extends EloquentRepository
{
    public function __construct(TagerSmsTemplate $model)
    {
        parent::__construct($model);
    }

    /**
     * @param $template
     * @return TagerSmsTemplate|null
     */
    public function findByTemplate($template)
    {
        return TagerSmsTemplate::whereTemplate($template)->first();
    }
}
