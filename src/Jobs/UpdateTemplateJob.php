<?php

namespace OZiTAG\Tager\Backend\Sms\Jobs;

use OZiTAG\Tager\Backend\Core\Jobs\Job;
use OZiTAG\Tager\Backend\Sms\Models\TagerSmsTemplate;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsTemplateRepository;
use OZiTAG\Tager\Backend\Sms\Requests\UpdateTemplateRequest;

class UpdateTemplateJob extends Job
{
    /**
     * @var UpdateTemplateRequest
     */
    private $request;

    /**
     * @var Object
     */
    private $model;

    public function __construct(UpdateTemplateRequest $request, ?TagerSmsTemplate $model = null)
    {
        $this->request = $request;

        $this->model = $model;
    }

    public function handle(SmsTemplateRepository $smsTemplateRepository)
    {
        $smsTemplateRepository->set($this->model);

        return $smsTemplateRepository->fillAndSave([
            'body' => $this->request->body,
            'recipients' => $this->request->recipients ? implode(',', array_unique($this->request->recipients)) : null,
            'changed_by_admin' => true
        ]);
    }
}
