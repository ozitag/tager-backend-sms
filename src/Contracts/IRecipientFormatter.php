<?php

namespace OZiTAG\Tager\Backend\Sms\Contracts;

interface IRecipientFormatter
{
    public function format($recipient);
}
