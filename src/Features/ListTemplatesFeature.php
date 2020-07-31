<?php

namespace OZiTAG\Tager\Backend\Sms\Features;

use OZiTAG\Tager\Backend\Core\Features\Feature;

class ListTemplatesFeature extends Feature
{
    public function handle(MailTemplateRepository $repository)
    {
        return MailTemplateResource::collection($repository->all());
    }
}
