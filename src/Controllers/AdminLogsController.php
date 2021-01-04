<?php

namespace OZiTAG\Tager\Backend\Sms\Controllers;

use OZiTAG\Tager\Backend\Crud\Actions\IndexAction;
use OZiTAG\Tager\Backend\Crud\Controllers\CrudController;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;

class AdminLogsController extends CrudController
{
    protected bool $hasIndexAction = true;

    protected bool $hasViewAction = false;

    protected bool $hasStoreAction = false;

    protected bool $hasUpdateAction = false;

    protected bool $hasDeleteAction = false;

    protected bool $hasMoveAction = false;

    public function __construct(SmsLogRepository $repository)
    {
        parent::__construct($repository);

        $this->setResourceFields([
            'id',
            'recipient',
            'body',
            'status',
            'error',
            'serviceResponse' => 'service_response',
            'createdAt' => 'created_at:datetime',
            'updatedAt' => 'updated_at:datetime'
        ]);

        $this->setIndexAction((new IndexAction())->enablePagination()->enableSearchByQuery());
    }

}
