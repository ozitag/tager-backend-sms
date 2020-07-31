<?php

namespace OZiTAG\Tager\Backend\Sms\Console;

use Illuminate\Console\Command;
use OZiTAG\Tager\Backend\Sms\Repositories\SmsTemplateRepository;
use OZiTAG\Tager\Backend\Sms\Utils\TagerSmsConfig;

class FlushSmsTemplatesCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'tager:sms-flush';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync DB Sms templates with config';

    public function handle(SmsTemplateRepository $repository)
    {
        if (TagerSmsConfig::hasDatabase() == false) {
            return;
        }

        var_dump(1);
        exit;

        $templates = config()->get('tager-sms.templates');
        if (!$templates) {
            return;
        }

        foreach ($templates as $template => $data) {
            $model = $repository->findByTemplate($template);

            if (!$model) {
                $model = $repository->createModelInstance();
                $model->template = $template;
            }

            if ($model->changed_by_admin == false) {
                if (isset($data['recipients'])) {
                    if (is_array($data['recipients'])) {
                        $model->recipients = implode(',', $data['recipients']);
                    } else if (is_string($data['recipients'])) {
                        $model->recipients = $data['recipients'];
                    }
                }

                $model->body = $data['value'] ?? '';
            }

            $model->name = $data['name'];
            $model->save();
        }
    }
}
