<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

use OZiTAG\Tager\Backend\Sms\Contracts\IService;

abstract class BaseService implements IService
{
    protected $params = [];

    public function init($params)
    {
        $this->params = $params;
    }

    protected function param($param, $default = null)
    {
        return isset($this->params[$param]) ? $this->params[$param] : $default;
    }
}
