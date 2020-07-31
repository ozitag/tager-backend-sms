<?php

namespace OZiTAG\Tager\Backend\Sms\Controllers;

use OZiTAG\Tager\Backend\Crud\Controllers\CrudController;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsLogRepository;

class AdminLogsController extends CrudController
{
    protected $hasIndexAction = true;

    protected $hasViewAction = false;

    protected $hasStoreAction = false;

    protected $hasUpdateAction = false;

    protected $hasDeleteAction = false;

    protected $hasMoveAction = false;

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
    }

}
