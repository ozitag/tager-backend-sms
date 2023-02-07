<?php

namespace OZiTAG\Tager\Backend\Sms\Contracts;

interface IService
{
    public function send(string $recipient, string $message, array $options = []);

    public function getResponse();
}
