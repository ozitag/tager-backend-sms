<?php

namespace OZiTAG\Tager\Backend\Sms\Services;

interface IService
{
    public function send($recipient, $message);
}
