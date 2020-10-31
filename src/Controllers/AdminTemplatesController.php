<?php

namespace OZiTAG\Tager\Backend\Sms\Controllers;

use OZiTAG\Tager\Backend\Crud\Actions\StoreOrUpdateAction;
use OZiTAG\Tager\Backend\Crud\Controllers\CrudController;
use OZiTAG\Tager\Backend\Sms\Jobs\UpdateTemplateJob;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsTemplateRepository;
use OZiTAG\Tager\Backend\Sms\Requests\UpdateTemplateRequest;
use OZiTAG\Tager\Backend\Sms\Utils\TagerSmsConfig;

class AdminTemplatesController extends CrudController
{
    protected $hasIndexAction = true;

    protected $hasViewAction = true;

    protected $hasStoreAction = false;

    protected $hasUpdateAction = true;

    protected $hasDeleteAction = false;

    protected $hasMoveAction = false;

    public function __construct(SmsTemplateRepository $repository)
    {
        parent::__construct($repository);

        $this->setResourceFields([
            'id',
            'template',
            'name',
            'body',
            'recipients' => function ($item) {
                return $item->recipients ? explode(',', $item->recipients) : [];
            }
        ]);

        $this->setFullResourceFields([
            'id',
            'template',
            'name',
            'body',
            'recipients' => function ($item) {
                return $item->recipients ? explode(',', $item->recipients) : [];
            },
            'fields' => function ($item) {
                return TagerSmsConfig::getTemplateFields($item->template);
            }
        ]);

        $this->setUpdateAction(new StoreOrUpdateAction(UpdateTemplateRequest::class, UpdateTemplateJob::class));
    }
}
