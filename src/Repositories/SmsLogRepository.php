<?php

namespace OZiTAG\Tager\Backend\Sms\Repositories;

use Illuminate\Contracts\Database\Eloquent\Builder;
use OZiTAG\Tager\Backend\Core\Repositories\EloquentRepository;
use OZiTAG\Tager\Backend\Core\Repositories\ISearchable;
use OZiTAG\Tager\Backend\Sms\Models\TagerSmsLog;

class SmsLogRepository extends EloquentRepository implements ISearchable
{
    public function __construct(TagerSmsLog $model)
    {
        parent::__construct($model);
    }

    public function searchByQuery(?string $query, Builder $builder = null): ?Builder
    {
        $builder = $builder ?: $this->model;

        return $builder
            ->orWhere('recipient', 'LIKE', '%' . $query . '%')
            ->orWhere('body', 'LIKE', '%' . $query . '%');
    }
}
