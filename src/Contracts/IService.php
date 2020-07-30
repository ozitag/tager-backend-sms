<?php

namespace OZiTAG\Tager\Backend\Sms\Contracts;

interface IService
{
    public function send($recipient, $message);

    public function getResponse();
}
